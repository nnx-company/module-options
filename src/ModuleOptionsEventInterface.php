<?php
/**
 * @link  https://github.com/nnx-framework/module-options
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
     * Имя модуля
     *
     * @return string
     */
    public function getModuleName();

    /**
     * Устанавливает имя модуля
     *
     * @param string $moduleName
     *
     * @return $this
     */
    public function setModuleName($moduleName);
}
