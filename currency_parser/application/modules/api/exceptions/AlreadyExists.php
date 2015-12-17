<?php

class Api_Exception_AlreadyExists extends Api_Exception_NoArgument
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
     * @return $this
     */
    public function setId($id)
    {
        $this->_id = $id;

        return $this;
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
     * @return $this
     */
    public function setIso($iso)
    {
        $this->_iso = $iso;
        $this->message = 'Currency with iso ' . $iso . ' already exists';

        return $this;
    }
}
