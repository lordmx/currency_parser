<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     * @inheritdoc
     */
    public function _initRoute()
    {
        $front = Zend_Controller_Front::getInstance();
        $router = $front->getRouter();

        $route = new Zend_Controller_Router_Route(
            'api/:version/currencies/',
            [
                'controller' => 'currency',
                'module'     => 'api'
            ],
            [
                'version' => 'v[\d]+'
            ]
        );
        $router->addRoute('currencies', $route);

        $targetRoute = new Zend_Controller_Router_Route(
            'api/:version/currencies/:id/',
            [
                'controller' => 'currency',
                'module'     => 'api',
            ],
            [
                'version' => 'v[\d]+',
                'id'      => '[\d]+'
            ]
        );
        $router->addRoute('currencies_with_id', $targetRoute);

        $front->setRequest('Api_Controller_Request');
    }
}

