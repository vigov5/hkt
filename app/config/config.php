<?php

return new \Phalcon\Config([
    'database' => [
        'adapter'     => 'Mysql',
        'host'        => 'localhost',
        'username'    => 'hkt',
        'password'    => 'hkt',
        'dbname'      => 'hyakkaten',
        'charset'     => 'utf8',
    ],
    'application' => [
        'baseDir'           => __DIR__ . '/../../base/',
        'controllersDir'    => __DIR__ . '/../../app/controllers/',
        'modelsDir'         => __DIR__ . '/../../app/models/',
        'formsDir'          => __DIR__ . '/../../app/forms/',
        'viewsDir'          => __DIR__ . '/../../app/views/',
        'pluginsDir'        => __DIR__ . '/../../app/plugins/',
        'componentsDir'     => __DIR__ . '/../../app/components/',
        'libraryDir'        => __DIR__ . '/../../app/library/',
        'cacheDir'          => __DIR__ . '/../../app/cache/',
        'baseUri'           => isset($_SERVER['SERVER_NAME']) ? 'http://'.$_SERVER['SERVER_NAME'].'/' : '',
        'homeUrl'           => '/',
        'defaultController' => 'index',
    ],
    'mail' => [
        'from_name' => 'HKT TEAM - NO REPLY',
        'from_email' => 'no-reply@hkt.thangtd.com',
    ],
    'facebook' => [
        'appId' => '298356136994396',
        'secret' => 'b2a6a718fe3b6fccc4c9b1e29975ccae',
        'scope' => 'public_profile,email'
    ],
]);
