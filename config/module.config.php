<?php
/**
 * @link    https://github.com/nnx-framework/module-options
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\ModuleOptions;

$config = [
    Module::CONFIG_KEY => [
        //Имя класса события, бросаемого когда необходимо получить объект с настройками модуля
        'moduleOptionsEventClassName' => ModuleOptionsEvent::class,
        //Суффикс прибавляемый к имени модуля. Ожидается что получившиеся строка, будет именем класса настроек модуля
        'moduleOptionsClassNameSuffix' => 'Options\\ModuleOptions'
    ]
];

return array_merge_recursive(
    include __DIR__ . '/moduleOptions.config.php',
    include __DIR__ . '/serviceManager.config.php',
    $config
);