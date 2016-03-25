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
     * Проверяет можно ли по имени класса, получить объект настроек модуля, к которому принадлежит данный класс
     *
     * @param string $className
     *
     * @return boolean
     */
    public function hasOptionsByClassName($className);

    /**
     * По имени класса, возвращает имя модуля к которому принадлежит данный класс
     *
     * @param $className
     *
     * @return string
     */
    public function getModuleNameByClassName($className);


    /**
     * Проверяет есть ли возможность определить имя модуля, по имени класса входящим в этот модуль
     *
     * @param $className
     *
     * @return boolean
     */
    public function hasModuleNameByClassName($className);

    /**
     * По имени модуля возвращает объект с его настройками
     *
     * @param string $moduleName
     *
     * @return ModuleOptionsInterface
     */
    public function getOptionsByModuleName($moduleName);

    /**
     * По имени модуля осуществляет проверку, есть ли у данного модуля настройки
     *
     * @param string $moduleName
     *
     * @return boolean
     */
    public function hasOptionsByModuleName($moduleName);
}
