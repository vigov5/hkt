<?php

return new \Phalcon\Config([
    'database' => [
        'adapter'     => 'Mysql',
        'host'        => 'localhost',
        'username'    => 'hkt',
        'password'    => 'hkt',
        'dbname'      => 'hyakkaten',
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
        'baseUri'           => 'http://'.$_SERVER['SERVER_NAME'].'/',
        'homeUrl'           => '/',
        'defaultController' => 'index',
    ],
    'mail' => [
        'from_name' => 'HKT TEAM',
        'from_email' => 'admin@hkt.thangtd.com',
        'smtp' => [
            'server' => 'smtp.gmail.com',
            'port' => 587,
            'security' => 'tls',
            'username' => 'framgia.email.tester@gmail.com',
            'password' => 'framgia345',
        ],
    ],
    'facebook' => [
        'appId' => '653649021339450',
        'secret' => '255a594fea0f194f932b3f21c21f3e2a',
        'scope' => 'basic_info,email'
    ],
]);
