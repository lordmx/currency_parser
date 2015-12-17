<?php

class Api_Controller_Request extends Zend_Controller_Request_Http
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
        $version = $this->getParam('version');
        $version = $version ?: 1;

        if ($version[0] == 'v') {
            $version = substr($version, 1);
        }

        return (int) $version;
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
