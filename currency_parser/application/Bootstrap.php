<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     * @inheritdoc
     */
    protected function _initLoader()
    {
        $apiLoader = new Zend_Loader_Autoloader_Resource([
            'namespace' => 'Api',
            'basePath'  => APPLICATION_PATH . '/modules/api',
        ]);

        $apiLoader
            ->addResourceType('dto', 'dto', 'Dto')
            ->addResourceType('annotation', 'annotations', 'Annotation')
            ->addResourceType('controller', 'controllers', 'Controller')
            ->addResourceType('parser', 'parsers', 'CurrencyParser')
            ->addResourceType('exception', 'exceptions', 'Exception')
            ->addResourceType('manager', 'managers', 'Manager')
            ->addResourceType('model', 'models', 'Model')
            ->addResourceType('service', 'services', 'Service')
            ->addResourceType('router', 'routers', 'Router')
            ->addResourceType('repository', 'repositories', 'Repository');
    }

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

        if (PHP_SAPI == 'cli') {
            $this->bootstrap('FrontController');

            $front = $this->getResource('FrontController');
            $front->setParam('disableOutputBuffering', true);
            $front->setRouter(new Api_Router_Cli());
            $front->setRequest(new Zend_Controller_Request_Simple());
        }
    }

    /**
     * @inheritdoc
     */
    protected function _initError ()
    {
        $this->bootstrap('FrontController');

        $front = $this->getResource('FrontController');
        $front->registerPlugin(new Zend_Controller_Plugin_ErrorHandler());
        $error = $front->getPlugin('Zend_Controller_Plugin_ErrorHandler');
        $error->setErrorHandlerController('index');

        if (PHP_SAPI == 'cli') {
            $error->setErrorHandlerController('error');
            $error->setErrorHandlerAction('cli');
        }
    }
}

