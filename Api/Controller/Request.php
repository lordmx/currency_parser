<?php

class Api_Controller_Request extends Zend_Controller_Request_Abstract
{
    /**
     * Get ID from path (or null if not exists)
     *
     * @return int|null
     */
    public function getId()
    {
        return (int) $this->getParam('id') ?: null;
    }

    /**
     * Get version number from path
     *
     * @return int
     */
    public function getVersion()
    {
        return (int) $this->getParam('version');
    }

    /**
     * @return int|null
     */
    public function getLimit()
    {
        return $this->getParam('limit') ? (int) $this->getParam('limit') : null;
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return (int) $this->getParam('offset');
    }
}
