<?php
/**
 * @link  https://github.com/nnx-framework/module-options
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\ModuleOptions;

/**
 * Interface ModuleConfigKeyProviderInterface
 *
 * @package Nnx\ModuleOptions
 */
interface ModuleConfigKeyProviderInterface
{
    /**
     * Возвращает ключ по которому из конфигов приложения, можно получить конфиг модуля
     *
     * @return string
     */
    public function getModuleConfigKey();
}
