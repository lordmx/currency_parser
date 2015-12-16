<?php

class Api_Manager_Dto
{
    /**
     * Get DTO from controller-action
     *
     * @param object $controller
     * @param string $action
     * @return object
     * @throws Api_Exception_BadMethodCall
     * @throws Api_Exception_InternalError
     */
    public function createFromMethod($controller, $action)
    {
        $reflection = new \ReflectionClass($controller);

        try {
            $arguments = $reflection->getMethod($action)->getParameters();
        } catch (\ReflectionException $e) {
            throw (new Api_Exception_BadMethodCall())->setMethod($this->getHttpMethodFromAction($action));
        }

        $className = $arguments[0]->getDeclaringClass();

        if (!$className) {
            return null;
        }

        if (!class_exists($className)) {
            throw new Api_Exception_InternalError('Wrong DTO in actions request');
        }

        $dto = new $className();

        return $dto;
    }

    /**
     * @param string $action
     * @return string
     */
    private function getHttpMethodFromAction($action)
    {
        return substr($action, 0, -strlen('Action'));
    }
}
