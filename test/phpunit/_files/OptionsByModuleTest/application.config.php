<?php

use Nnx\ModuleOptions\PhpUnit\TestData\TestPaths;


return [
    'modules'                 => [
        'Nnx\\ModuleOptions',
        'Nnx\\ModuleOptions\\PhpUnit\\TestData\\OptionsByModuleTest\\TestModule1',
        'Nnx\\ModuleOptions\\PhpUnit\\TestData\\OptionsByModuleTest\\TestModule2',

    ],
    'module_listener_options' => [
        'module_paths'      => [
            'Nnx\\ModuleOptions' => TestPaths::getPathToModule(),
            'Nnx\\ModuleOptions\\PhpUnit\\TestData\\OptionsByModuleTest\\TestModule1' => TestPaths::getPathToOptionsByModuleTestAppModuleDir() . 'TestModule1',
            'Nnx\\ModuleOptions\\PhpUnit\\TestData\\OptionsByModuleTest\\TestModule2' => TestPaths::getPathToOptionsByModuleTestAppModuleDir() . 'TestModule2',

        ],
        'config_glob_paths' => [
            __DIR__ . '/config/autoload/{{,*.}global,{,*.}local}.php',
        ],
    ]
];
