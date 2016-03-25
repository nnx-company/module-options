<?php
/**
 * @link    https://github.com/nnx-framework/module-options
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\ModuleOptions;

return [
    'service_manager' => [
        'invokables'         => [

        ],
        'factories'          => [
            ModuleOptionsPluginManagerInterface::class => ModuleOptionsPluginManagerFactory::class
        ],
        'abstract_factories' => [

        ]
    ],
];


