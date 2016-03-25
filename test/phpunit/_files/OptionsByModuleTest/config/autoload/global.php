<?php
/**
 * @link  https://github.com/nnx-framework/module-options
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\ModuleOptions\PhpUnit\TestData\OptionsByModuleTest;

use Nnx\ModuleOptions\ModuleOptionsPluginManager;
use Nnx\ModuleOptions\PhpUnit\TestData\OptionsByModuleTest\TestModule1\Options\ModuleOptions as ModuleOptions1;
use Nnx\ModuleOptions\PhpUnit\TestData\OptionsByModuleTest\TestModule2\Options\ModuleOptions as ModuleOptions2;
use Nnx\ModuleOptions\PhpUnit\TestData\OptionsByModuleTest\TestModule3\Options\ModuleOptions as ModuleOptions3;

return [
    ModuleOptionsPluginManager::CONFIG_KEY => [
        'invokables' => [
            ModuleOptions1::class => ModuleOptions1::class,
            ModuleOptions2::class => ModuleOptions2::class,
            ModuleOptions3::class => ModuleOptions3::class,
        ]
    ]
];