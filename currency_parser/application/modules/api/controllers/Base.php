<?php

class Api_Controller_Base extends Zend_Controller_Action
{
    /**
     * @var Api_Manager_Dto
     */
    private $_dtoManager;

    /**
     * @var Api_Manager_Annotation
     */
    private $_annotationManager;

    /**
     * @var int
     */
    protected $_defaultLimit = 10;

    /**
     * @var string
     */
    protected $_resourceName;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->_dtoManager = new Api_Manager_Dto();
        $this->_annotationManager = new Api_Manager_Annotation(
            new Api_Annotation_Reader_Simple(),
            new Api_Annotation_Injector_Simple()
        );
    }

    /**
     * @param string $action
     * @return Api_Controller_Response_Result
     * @throws Api_Exception_BadMethodCall
     */
    public function dispatch($action)
    {
        try {
            $dto = $this->_dtoManager->createFromMethod($this, $action);
        } catch (Api_Exception_BadMethodCall $e) {
            throw new Api_Exception_BadMethodCall();
        }

        try {
            if (!$dto) {
                parent::dispatch($action);
            } else {
                $this->_annotationManager->populateDtoFromArray($dto, $this->getAllParams());
                $result = call_user_func_array([$this, $action], [$dto]);
            }
        } catch (Api_Exception_BadMethodCall $e) {
            $result = $this->_createError('not found', Api_Controller_Response_Result::NOT_FOUND);
        } catch (Api_Exception_NoArgument $e) {
            $result = $this->_createError('bad request', Api_Controller_Response_Result::BAD_REQUEST);
        } catch (Exception $e) {
            $result = $this->_createError('internal error', Api_Controller_Response_Result::INTERNAL);
        }

        $this->_response->setHttpResponseCode($result->getCode());

        if ($result->getLocation()) {
            $this->_response->setHeader('Location', $result->getLocation());
        }

        $raw = $this->_getRawResult($result);

        $this->_helper->json($raw);
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->_request->getLimit() ?: $this->_defaultLimit;
    }

    /**
     * @param Api_Controller_Response_Result $result
     * @return array
     */
    protected function _getRawResult(Api_Controller_Response_Result $result)
    {
        $raw = [];

        if ($result->getMeta()) {
            $raw['metadata'] = [
                'resultset' => $result->getMeta()->toArray()
            ];

            if ($result->getMeta()->getData()) {
                $raw['metadata'] = array_merge($raw['metadata'], $result->getMeta()->getData());
            }
        }

        if ($result->getError()) {
            $raw['error'] = $result->getError();
        } else {
            $raw[$this->_resourceName] = [];
        }

        if ($result->getEntities()) {
            $entities = [];

            foreach ($result->getEntities() as $entity) {
                $dto = $this->_getDto($entity);
                $entities[] = $this->_annotationManager->populateArrayFromDto($dto);
            }

            $raw[$this->_resourceName] = $entities;
        }

        return $raw;
    }

    /**
     * @param mixed $entity
     * @return mixed
     */
    protected function _getDecoratedEntity($entity)
    {
        return $entity;
    }

    /**
     * @param object $entity
     * @return object
     * @throws Api_Exception_BadMethodCall
     * @throws Api_Exception_InternalError
     */
    protected function _getDto($entity)
    {
        if ($entity instanceof Api_Model_Base) {
            $dto = $this->_dtoManager->createFromMethod($this, $this->_request->getActionName() . 'Action');
            $this->_annotationManager->populateDtoFromModel($dto, $this->_getDecoratedEntity($entity));
        } else {
            $dto = $this->_getDecoratedEntity($entity);
        }

        return $dto;
    }

    /**
     * @param object[] $entities
     * @param Api_Controller_Response_Meta $meta
     * @param int $code
     * @return Api_Controller_Response_Result
     */
    protected function _createResult(
        $entities,
        Api_Controller_Response_Meta $meta = null,
        $code = Api_Controller_Response_Result::OK
    ) {
        return new Api_Controller_Response_Result($entities, $meta, $code);
    }

    /**
     * @param int $count
     * @param int|null $limit
     * @param int|null $offset
     * @return Api_Controller_Response_Meta
     */
    protected function _createMeta($count, $limit = null, $offset = null)
    {
        return new Api_Controller_Response_Meta(
            $count,
            $limit ?: $this->getLimit(),
            $offset ?: $this->_request->getOffset()
        );
    }

    /**
     * @param string $message
     * @param int $code
     * @return Api_Controller_Response_Result
     */
    protected function _createError($message, $code)
    {
        $result = new Api_Controller_Response_Result([], null, $code);
        $result->setError($message);

        return $result;
    }

    /**
     * @return string
     */
    protected function _getUrl()
    {
        return '/api/' . $this->_request->getVersion() . '/' . $this->_resounseName . '/';
    }
}