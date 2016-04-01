<?php
/**
 * @link  https://github.com/nnx-framework/module-options
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
use \Nnx\ModuleOptions\PhpUnit\TestData\TestPaths;

return [
    'modules'                 => [
        'Nnx\\ModuleOptions',
        'Nnx\\ModuleOptions\\PhpUnit\\TestData\\DefaultModuleOptionsAbstractFactory\\TestModule1',
        'Nnx\\ModuleOptions\\PhpUnit\\TestData\\DefaultModuleOptionsAbstractFactory\\TestModule2',
        'Nnx\\ModuleOptions\\PhpUnit\\TestData\\DefaultModuleOptionsAbstractFactory\\TestModule3',

    ],
    'module_listener_options' => [
        'module_paths'      => [
            'Nnx\\ModuleOptions' => TestPaths::getPathToModule(),
            'Nnx\\ModuleOptions\\PhpUnit\\TestData\\DefaultModuleOptionsAbstractFactory\\TestModule1' => TestPaths::getPathToDefaultModuleOptionsAbstractFactoryAppModuleDir() . 'TestModule1',
            'Nnx\\ModuleOptions\\PhpUnit\\TestData\\DefaultModuleOptionsAbstractFactory\\TestModule2' => TestPaths::getPathToDefaultModuleOptionsAbstractFactoryAppModuleDir() . 'TestModule2',
            'Nnx\\ModuleOptions\\PhpUnit\\TestData\\DefaultModuleOptionsAbstractFactory\\TestModule3' => TestPaths::getPathToDefaultModuleOptionsAbstractFactoryAppModuleDir() . 'TestModule3',

        ],
        'config_glob_paths' => [
            __DIR__ . '/config/autoload/{{,*.}global,{,*.}local}.php',
        ],
    ]
];
