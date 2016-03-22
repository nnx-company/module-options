<?php
/**
 * @link  https://github.com/nnx-company/module-options
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\ModuleOptions;

/**
 * Interface ModuleOptionsProviderInterface
 *
 * @package Nnx\ModuleOptions
 */
interface ModuleOptionsProviderInterface
{
    /**
     * Возвращает настройкий для ModuleOptionsPluginManagerInterface
     *
     * @return array
     */
    public function getModuleOptionsConfig();
}
