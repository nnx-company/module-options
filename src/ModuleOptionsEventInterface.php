<?php
/**
 * @link  https://github.com/nnx-company/module-options
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\ModuleOptions;

use Zend\EventManager\EventInterface;

/**
 * Interface ModuleOptionsEventInterface
 *
 * @package Nnx\ModuleOptions
 */
interface ModuleOptionsEventInterface extends EventInterface
{
    /**
     * @string
     */
    const LOAD_MODULE_OPTIONS_EVENT = 'moduleOptions.load';

    /**
     * Имя класса модуля
     *
     * @return string
     */
    public function getModuleClassName();

    /**
     * Устанавливает имя класса модуля
     *
     * @param string $moduleClassName
     *
     * @return $this
     */
    public function setModuleClassName($moduleClassName);
}
