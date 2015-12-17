<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../../vendor/zendframework/zendframework1/library'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

error_reporting(E_ALL);

require_once 'Zend/Loader/Autoloader.php';
$loader = Zend_Loader_Autoloader::getInstance();

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
    ->addResourceType('repository', 'repositories', 'Repository');

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()->run();