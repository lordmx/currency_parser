<?php

class Api_Exception_AlreadyExists extends Api_Exception_Base
{
    /**
     * @var int
     */
    private $_id;

    /**
     * @var string
     */
    private $_iso;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @return string
     */
    public function getIso()
    {
        return $this->_iso;
    }

    /**
     * @param string $iso
     */
    public function setIso($iso)
    {
        $this->_iso = $iso;
    }
}
