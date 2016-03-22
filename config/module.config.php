<?php
/**
 * @link    https://github.com/nnx-company/module-options
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\ModuleOptions;

$config = [
    Module::CONFIG_KEY => [
        'moduleOptionsEventClassName' => ModuleOptionsEvent::class,
        'moduleOptionsClassNameSuffix' => 'Options\\ModuleOptions'
    ]
];

return array_merge_recursive(
    include __DIR__ . '/moduleOptions.config.php',
    include __DIR__ . '/serviceManager.config.php',
    $config
);