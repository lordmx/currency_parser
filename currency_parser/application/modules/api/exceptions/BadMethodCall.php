<?php

class Api_Exception_BadMethodCall extends Api_Exception_Base
{
    /**
     * @var string
     */
    private $_method;

    /**
     * @param string $method
     * @return $this
     */
    public function setMethod($method)
    {
        $this->_method = $method;

        return $this;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->_method;
    }
}
