<?php

use Phalcon\DI\FactoryDefault\CLI as CliDI,
    Phalcon\CLI\Console as ConsoleApp,
    Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter,
    Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;

define('VERSION', '1.0.0');

//Using the CLI factory default services container
$di = new CliDI();

// Define path to application directory
defined('APPLICATION_PATH')
|| define('APPLICATION_PATH', realpath(dirname(__FILE__) . "/../"));

/**
 * Register the autoloader and tell it to register the tasks directory
 */

// Load the configuration file (if any)
if (is_readable(APPLICATION_PATH . '/config/config.php')) {
    $config = include APPLICATION_PATH . '/config/config.php';
    $di->set('config', $config);
}

$loader = new \Phalcon\Loader();
$loader->registerDirs([
        $config->application->baseDir,
        $config->application->controllersDir,
        $config->application->modelsDir,
        $config->application->pluginsDir,
        $config->application->formsDir,
        $config->application->componentsDir,
        APPLICATION_PATH . '/console/tasks'
    ]
);
$loader->register();

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->set('url', function () use ($config) {
        $url = new UrlResolver();
        $url->setBaseUri($config->application->baseUri);

        return $url;
    }, true
);

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
                            ]
                        );

                        return $volt;
                    },
                '.phtml' => 'Phalcon\Mvc\View\Engine\Php'
            ]
        );

        return $view;
    }, true
);

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->set('db', function () use ($config) {
        return new DbAdapter([
            'host' => $config->database->host,
            'username' => $config->database->username,
            'password' => $config->database->password,
            'dbname' => $config->database->dbname,
            'charset' => 'utf8',
        ]);
    }
);

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->set('modelsMetadata', function () {
        return new MetaDataAdapter();
    }
);

$di->set('mail', function () {
        return new Mail();
    }
);

//Create a console application
$console = new ConsoleApp();
$console->setDI($di);

/**
 * Process the console arguments
 */
$arguments = [];
foreach ($argv as $k => $arg) {
    if ($k == 1) {
        $arguments['task'] = $arg;
    } elseif ($k == 2) {
        $arguments['action'] = $arg;
    } elseif ($k >= 3) {
        $arguments[] = $arg;
    }
}

// define global constants for the current task and action
define('CURRENT_TASK', (isset($argv[1]) ? $argv[1] : null));
define('CURRENT_ACTION', (isset($argv[2]) ? $argv[2] : null));

try {
    // handle incoming arguments
    $console->handle($arguments);
} catch (\Phalcon\Exception $e) {
    echo $e->getMessage();
    exit(255);
}