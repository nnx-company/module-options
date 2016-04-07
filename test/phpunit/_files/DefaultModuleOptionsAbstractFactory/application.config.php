<?php
/**
 * @link  https://github.com/nnx-framework/module-options
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
use \Nnx\ModuleOptions\PhpUnit\TestData\TestPaths;

use Nnx\ModuleOptions\Module;
use Nnx\ModuleOptions\PhpUnit\TestData\DefaultModuleOptionsAbstractFactory\TestModule1\Module as TestModule1;
use Nnx\ModuleOptions\PhpUnit\TestData\DefaultModuleOptionsAbstractFactory\TestModule2\Module as TestModule2;
use Nnx\ModuleOptions\PhpUnit\TestData\DefaultModuleOptionsAbstractFactory\TestModule3\Module as TestModule3;

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
            TestModule1::MODULE_NAME => TestPaths::getPathToDefaultModuleOptionsAbstractFactoryAppModuleDir() . 'TestModule1',
            TestModule2::MODULE_NAME => TestPaths::getPathToDefaultModuleOptionsAbstractFactoryAppModuleDir() . 'TestModule2',
            TestModule3::MODULE_NAME => TestPaths::getPathToDefaultModuleOptionsAbstractFactoryAppModuleDir() . 'TestModule3',

        ],
        'config_glob_paths' => [
            __DIR__ . '/config/autoload/{{,*.}global,{,*.}local}.php',
        ],
    ]
];
