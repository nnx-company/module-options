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
 *
 * @method ModuleOptionsInterface get($id)
 */
interface ModuleOptionsPluginManagerInterface extends ContainerInterface
{
    /**
     * Возвращает конфиг модуля, по имиени любого класса из данного модуля
     *
     * @param string $className
     *
     * @return ModuleOptionsInterface
     */
    public function getOptionsByClassName($className);

    /**
     * По имени класса, возвращает имя модуля к которому принадлежит данный класс
     *
     * @param $className
     *
     * @return string
     */
    public function getModuleNameByClassName($className);


    /**
     * По имени модуля возвращает объект с его настройками
     *
     * @param string $moduleName
     *
     * @return ModuleOptionsInterface
     */
    public function getOptionsByModuleName($moduleName);
}
