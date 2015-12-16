<?php

class Api_Exception_NoArgument extends Api_Exception_Base
{
    /**
     * @var string
     */
    private $_name;

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->_name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }
}
