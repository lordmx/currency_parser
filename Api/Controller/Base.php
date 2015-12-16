<?php

class Api_Controller_Base extends Zend_Rest_Controller
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
     * @throws Api_Exception_NotFound
     */
    public function dispatch($action)
    {
        try {
            $dto = $this->_dtoManager->createFromMethod($this, $action);
        } catch (Api_Exception_BadMethodCall $e) {
            throw new Api_Exception_NotFound();
        }

        try {
            if (!$dto) {
                parent::dispatch($action);
            } else {
                $this->_annotationManager->processRequest($dto, $this->getAllParams());
                call_user_func_array([$this, $action], [$dto]);
            }
        } catch (Api_Exception_BadMethodCall $e) {
            return $this->_createError('not found', Api_Response_Result::NOT_FOUND);
        } catch (Api_Exception_NoArgument $e) {
            return $this->_createError('bad request', Api_Response_Result::BAD_REQUEST);
        } catch (Api_Exception_Base $e) {
            return $this->_createError('internal error', Api_Response_Result::INTERNAL);
        }
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->_request->getLimit() ?: $this->_defaultLimit;
    }

    /**
     * @param object[] $entities
     * @param Api_Response_Meta $meta
     * @param int $code
     * @return Api_Response_Result
     */
    protected function _createResult($entities, Api_Response_Meta $meta = null, $code = Api_Response_Result::OK)
    {
        return new Api_Response_Result($entities, $meta, $code);
    }

    /**
     * @param int $count
     * @param int|null $limit
     * @param int|null $offset
     * @return Api_Response_Meta
     */
    protected function _createMeta($count, $limit = null, $offset = null)
    {
        return new Api_Response_Meta(
            $count,
            $limit ?: $this->getLimit(),
            $this->_request->getOffset()
        );
    }

    /**
     * @param string $message
     * @param int $code
     * @return Api_Response_Result
     */
    protected function _createError($message, $code)
    {
        $result = new Api_Response_Result([], null, $code);
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