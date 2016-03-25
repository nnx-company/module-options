<?php
/**
 * @link  https://github.com/nnx-framework/module-options
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\ModuleOptions\Options;

use Zend\Stdlib\AbstractOptions;
use Nnx\ModuleOptions\ModuleOptionsInterface;

/**
 * Interface ModuleOptionsInterface
 *
 * @package Nnx\ModuleOptions
 */
class ModuleOptions extends AbstractOptions implements ModuleOptionsInterface
{
    /**
     * Имя класса события бросаемого когда нужно по имени модуля получить объект с настройками модуля
     *
     * @var string
     */
    protected $moduleOptionsEventClassName;

    /**
     * Часть имени класса на которую оканчивается класс описывающий опции модуля.
     *
     *
     * @var string
     */
    protected $moduleOptionsClassNameSuffix;

    /**
     * Имя класса события бросаемого когда нужно по имени модуля получить объект с настройками модуля
     *
     * @return string
     */
    public function getModuleOptionsEventClassName()
    {
        return $this->moduleOptionsEventClassName;
    }

    /**
     * Имя класса события бросаемого когда нужно по имени модуля получить объект с настройками модуля
     *
     * @param string $moduleOptionsEventClassName
     *
     * @return $this
     */
    public function setModuleOptionsEventClassName($moduleOptionsEventClassName)
    {
        $this->moduleOptionsEventClassName = $moduleOptionsEventClassName;

        return $this;
    }

    /**
     * Часть имени класса на которую оканчивается класс описывающий опции модуля.
     *
     * @return string
     */
    public function getModuleOptionsClassNameSuffix()
    {
        return $this->moduleOptionsClassNameSuffix;
    }

    /**
     * Часть имени класса на которую оканчивается класс описывающий опции модуля.
     *
     * @param string $moduleOptionsClassNameSuffix
     *
     * @return $this
     */
    public function setModuleOptionsClassNameSuffix($moduleOptionsClassNameSuffix)
    {
        $this->moduleOptionsClassNameSuffix = $moduleOptionsClassNameSuffix;

        return $this;
    }
}
