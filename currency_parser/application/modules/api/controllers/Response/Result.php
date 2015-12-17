<?php

class Api_Controller_Response_Result
{
    const OK = 200;
    const CREATED = 201;
    const NO_CONTENT = 204;
    const NOT_FOUND = 404;
    const BAD_REQUEST = 400;
    const INTERNAL = 500;

    /**
     * @var Api_Controller_Response_Meta
     */
    protected $_meta;

    /**
     * HTTP-code of the response
     *
     * @var int
     */
    protected $_code;

    /**
     * Result entities
     *
     * @var array
     */
    protected $_entities = [];

    /**
     * Url for Http-Location header
     *
     * @var string
     */
    protected $_location;

    /**
     * Error message (is exists)
     *
     * @var string
     */
    protected $_error;

    /**
     * @param array $entities
     * @param Api_Controller_Response_Meta|null $meta
     * @param int|null $code
     */
    public function __construct(array $entities, Api_Controller_Response_Meta $meta = null, $code = self::OK)
    {
        $this->_entities = $entities;
        $this->_meta = $meta;
        $this->_code = $code;
    }

    /**
     * @return Api_Controller_Response_Meta|null
     */
    public function getMeta()
    {
        return $this->_meta;
    }

    /**
     * @return array
     */
    public function getEntities()
    {
        return $this->_entities;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->_code;
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->_location;
    }

    /**
     * @param string $location
     * @return $this
     */
    public function setLocation($location)
    {
        $this->_location = $location;

        return $this;
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->_error;
    }

    /**
     * @param string $error
     * @return $this
     */
    public function setError($error)
    {
        $this->_error = $error;

        return $this;
    }
}
