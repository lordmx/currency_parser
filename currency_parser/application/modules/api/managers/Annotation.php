<?php

class Api_Manager_Annotation
{
    /**
     * @var Api_Annotation_Reader_Interface
     */
    private $_reader;

    /**
     * @var Api_Annotation_Cache_Interface
     */
    private $_cache;

    /**
     * @var Api_Annotation_Injector_Interface
     */
    private $_injector;

    /**
     * @param Api_Annotation_Reader_Interface $reader
     * @param Api_Annotation_Injector_Interface $injector
     */
    public function __construct(Api_Annotation_Reader_Interface $reader, Api_Annotation_Injector_Interface $injector)
    {
        $this->_reader = $reader;
        $this->_injector = $injector;
    }

    /**
     * @param object $dto
     * @param array $data
     * @return object
     * @throws Api_Exception_InternalError
     */
    public function populateDtoFromArray($dto, array $data = [])
    {
        $reflection = new ReflectionClass($dto);

        foreach ($this->_getProperties($dto) as $propertyName)
        {
            try {
                $this->_processProperty($dto, $reflection->getProperty($propertyName), $data);
            } catch (\ReflectionException $e) {
                throw new Api_Exception_InternalError($e->getMessage());
            }
        }

        return $dto;
    }

    /**
     * @param object $dto
     * @param ArrayAccess $model
     * @return object
     */
    public function populateDtoFromModel($dto, \ArrayAccess $model)
    {
        $reflection = new ReflectionClass($dto);

        foreach ($this->_getProperties($dto) as $propertyName)
        {
            $this->_processProperty($dto, $reflection->getProperty($propertyName), $model);
        }

        return $dto;
    }

    /**
     * @param object $dto
     * @return array
     */
    public function populateArrayFromDto($dto)
    {
        $reflection = new ReflectionClass($dto);
        $result = [];

        foreach ($this->_getProperties($dto) as $propertyName)
        {
            $annotations = null;
            $propertyReflection = $reflection->getProperty($propertyName);

            if ($this->_cache) {
                $annotations = $this->_cache->get($dto, $propertyReflection->getName());
            }

            if (is_null($annotations)) {
                $annotations = $this->_reader->read($propertyReflection->getDocComment());
            }

            $result = array_merge(
                $result,
                $this->_injector->injectToArrayFromDto($dto, $propertyReflection, $annotations)
            );

            if ($this->_cache) {
                $this->_cache->set($dto, $propertyReflection->getName(), $annotations);
            }
        }

        return $result;
    }

    /**
     * @param object $dto
     * @return array
     */
    private function _getProperties($dto)
    {
        return array_keys(get_class_vars(get_class($dto)));
    }

    /**
     * @param object $dto
     * @param \ReflectionProperty $reflection
     * @param array|\ArrayAccess
     * @throws Api_Exception_InternalError
     */
    private function _processProperty($dto, \ReflectionProperty $reflection, $source)
    {
        $annotations = null;

        if ($this->_cache) {
            $annotations = $this->_cache->get($dto, $reflection->getName());
        }

        if (is_null($annotations)) {
            $annotations = $this->_reader->read($reflection->getDocComment());
        }

        if (is_array($source)) {
            $this->_injector->injectToPropertyFromArray($dto, $reflection, $annotations, $source);
        } elseif ($source instanceof \ArrayAccess) {
            $this->_injector->injectToPropertyFromModel($dto, $reflection, $annotations, $source);
        } else {
            throw new Api_Exception_InternalError('Wrong annotations data source');
        }

        if ($this->_cache) {
            $this->_cache->set($dto, $reflection->getName(), $annotations);
        }
    }

    /**
     * @param Api_Annotation_Cache_Interface $cache
     */
    public function setCache(Api_Annotation_Cache_Interface $cache)
    {
        $this->_cache = $cache;
    }
}
