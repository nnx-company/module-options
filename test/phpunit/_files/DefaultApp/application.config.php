<?php

use Nnx\ModuleOptions\PhpUnit\TestData\TestPaths;
use Nnx\ModuleOptions\Module;

return [
    'modules'                 => [
        Module::MODULE_NAME
    ],
    'module_listener_options' => [
        'module_paths'      => [
            Module::MODULE_NAME => TestPaths::getPathToModule(),
        ],
        'config_glob_paths' => [
            __DIR__ . '/config/autoload/{{,*.}global,{,*.}local}.php',
        ],
    ]
];
