<?php
require __DIR__ . '/../library/pretty-exceptions/loader.php';

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs([
    $config->application->baseDir,
    $config->application->controllersDir,
    $config->application->modelsDir,
    $config->application->pluginsDir,
    $config->application->formsDir,
    $config->application->componentsDir,
])->register();
