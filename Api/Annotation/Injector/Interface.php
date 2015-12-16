<?php

interface Api_Annotation_Injector_Interface
{
    /**
     * Injector data to property using annotations (the source data will be received from array)
     *
     * @param object $dto Target object
     * @param ReflectionProperty $reflection Properties reflection (using for setting property and getting phpdoc)
     * @param Api_Annotation_Collection|null $annotations Collection of annotations
     * @param array $data Source array of data
     */
    public function injectFromArray(
        $dto,
        \ReflectionProperty $reflection,
        Api_Annotation_Collection $annotations,
        array $data = []
    );

    /**
     * Injector data to property using annotations (the source data will be received from model)
     *
     * @param object $dto Target object
     * @param ReflectionProperty $reflection Properties reflection (using for setting property and getting phpdoc)
     * @param Api_Annotation_Collection|null $annotations Collection of annotations
     * @param ArrayAccess $model Source model
     */
    public function injectFromModel(
        $dto,
        \ReflectionProperty $reflection,
        Api_Annotation_Collection $annotations,
        \ArrayAccess $model
    );
}
