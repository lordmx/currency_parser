<?php

class Api_Response_Meta
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
}
