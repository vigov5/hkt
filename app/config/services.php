<?php

use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;

/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 */
$di = new FactoryDefault();

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->set('url', function () use ($config) {
    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
}, true);

/**
 * Setting up the view component
 */
$di->set('view', function () use ($config) {

    $view = new View();

    $view->setViewsDir($config->application->viewsDir);

    $view->registerEngines([
        '.volt' => function ($view, $di) use ($config) {

            $volt = new VoltEngine($view, $di);

            $volt->setOptions([
                'compiledPath' => $config->application->cacheDir,
                'compiledSeparator' => '_',
            ]);

            return $volt;
        },
        '.phtml' => 'Phalcon\Mvc\View\Engine\Php'
    ]);

    return $view;
}, true);

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->set('db', function () use ($config) {
    return new DbAdapter([
        'host' => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname' => $config->database->dbname,
    ]);
});

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->set('modelsMetadata', function () {
    return new MetaDataAdapter();
});

/**
 * Start the session the first time some component request the session service
 */
$di->set('session', function () {
    $session = new SessionAdapter();
    $session->start();

    return $session;
});

$di->set('flash', function(){
    $flash = new \Phalcon\Flash\Direct([
        'error' => 'alert alert-dismissable alert-danger',
        'success' => 'alert alert-dismissable alert-success',
        'notice' => 'alert alert-dismissable alert-info',
    ]);
    return $flash;
});

$di->set('config', function() use ($config) {
    return $config;
}, true);

/**
 * Custom authentication component
 */
$di->set('auth', function () {
    return new Auth();
});

$di->set('dispatcher', function() {

    //Create an EventsManager
    $eventsManager = new Phalcon\Events\Manager();

    //Attach a listener
    $eventsManager->attach("dispatch:beforeException", new NotFoundException());

    $dispatcher = new \Phalcon\Mvc\Dispatcher();

    //Bind the EventsManager to the dispatcher
    $dispatcher->setEventsManager($eventsManager);

    return $dispatcher;

}, true);

$di->set('mail', function () {
    return new Mail();
});

$di->setShared('facebook', function() use ($config) {
    return new \Facebook([
        'appId'     => $config->facebook->appId,
        'secret'    => $config->facebook->secret
    ]);
});