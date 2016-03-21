<?php

use Nnx\ModuleOptions\PhpUnit\TestData\TestPaths;


return [
    'modules'                 => [
        'Nnx\\ModuleOptions'
    ],
    'module_listener_options' => [
        'module_paths'      => [
            'Nnx\\ModuleOptions' => TestPaths::getPathToModule(),
        ],
        'config_glob_paths' => [
            __DIR__ . '/config/autoload/{{,*.}global,{,*.}local}.php',
        ],
    ]
];
