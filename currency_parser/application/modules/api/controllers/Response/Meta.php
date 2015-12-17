<?php

class Api_Controller_Response_Meta
{
    /**
     * Skip the number of models from beginning
     *
     * @var int
     */
    protected $_offset = 0;

    /**
     * Limit of getting models for current request
     *
     * @var int
     */
    protected $_limit;

    /**
     * Total count of models (ignoring $offset and $limit)
     *
     * @var int
     */
    protected $_count;

    /**
     * @var array
     */
    protected $_data = [];

    /**
     * @param int $count
     * @param int $limit
     * @param int $offset
     */
    public function __construct($count, $limit, $offset = 0)
    {
        $this->_count = $count;
        $this->_limit = $limit;
        $this->_offset = $offset;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function addParam($key, $value)
    {
        $this->_data[$key] = $value;

        return $this;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->_count;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->_limit;
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->_offset;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->_data;
    }

    /**
     * @param array $data
     */
    public function setData($data)
    {
        $this->_data = $data;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'limit'  => $this->getLimit(),
            'offset' => $this->getOffset(),
            'count'  => $this->getCount(),
        ];
    }
}
