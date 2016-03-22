<?php
/**
 * @link    https://github.com/nnx-company/module-options
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\ModuleOptions;

use Nnx\ModuleOptions\Options\ModuleOptions;
use Nnx\ModuleOptions\Options\ModuleOptionsFactory;

return [
    ModuleOptionsPluginManager::CONFIG_KEY => [
        'factories' => [
            ModuleOptions::class => ModuleOptionsFactory::class
        ]
    ]
];