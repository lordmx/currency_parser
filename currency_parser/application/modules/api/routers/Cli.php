<?php

class Api_Router_Cli extends Zend_Controller_Router_Abstract
{
    /**
     * @inheritdoc
     * @throws Api_Exception_BadMethodCall
     */
    public function route(Zend_Controller_Request_Abstract $dispatcher)
    {
        $arguments = (new Zend_Console_Getopt([]))->getRemainingArgs();

        if ($arguments) {
            $command = array_shift($arguments);
            $action = array_shift($arguments);

            if (!$action) {
                $action = 'index';
            }

            if(!preg_match('~\W~', $command)) {
                $dispatcher->setControllerName($command);
                $dispatcher->setActionName($action);
                $dispatcher->setModuleName('api');

                $dispatcher->setParams($arguments);

                return $dispatcher;
            }
        }

        throw new Api_Exception_BadMethodCall();
    }

    /**
     * @inheritdoc
     */
    public function assemble($userParams, $name = null, $reset = false, $encode = true)
    {
        throw new \Exception('Not implemented yet');
    }
}