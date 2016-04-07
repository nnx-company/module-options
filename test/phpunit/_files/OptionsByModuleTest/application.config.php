<?php

use Nnx\ModuleOptions\PhpUnit\TestData\TestPaths;
use Nnx\ModuleOptions\Module;
use Nnx\ModuleOptions\PhpUnit\TestData\OptionsByModuleTest\TestModule1\Module as TestModule1;
use Nnx\ModuleOptions\PhpUnit\TestData\OptionsByModuleTest\TestModule2\Module as TestModule2;
use Nnx\ModuleOptions\PhpUnit\TestData\OptionsByModuleTest\TestModule3\Module as TestModule3;

return [
    'modules'                 => [
        Module::MODULE_NAME,
        TestModule1::MODULE_NAME,
        TestModule2::MODULE_NAME,
        TestModule3::MODULE_NAME,

    ],
    'module_listener_options' => [
        'module_paths'      => [
            Module::MODULE_NAME => TestPaths::getPathToModule(),
            TestModule1::MODULE_NAME => TestPaths::getPathToOptionsByModuleTestAppModuleDir() . 'TestModule1',
            TestModule2::MODULE_NAME => TestPaths::getPathToOptionsByModuleTestAppModuleDir() . 'TestModule2',
            TestModule3::MODULE_NAME => TestPaths::getPathToOptionsByModuleTestAppModuleDir() . 'TestModule3',

        ],
        'config_glob_paths' => [
            __DIR__ . '/config/autoload/{{,*.}global,{,*.}local}.php',
        ],
    ]
];
