<?php
/**
 * @link  https://github.com/nnx-company/module-options
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\ModuleOptions;

use Interop\Container\ContainerInterface;

/**
 * Interface ModuleOptionsPluginManagerInterface
 *
 * @package Nnx\ModuleOptions
 */
interface ModuleOptionsPluginManagerInterface extends ContainerInterface
{
    /**
     * Возвращает конфиг модуля, по имиени класса модуля
     *
     * @param string $moduleClassName
     *
     * @return ModuleOptionsInterface
     */
    public function getOptionsByModule($moduleClassName);
}
