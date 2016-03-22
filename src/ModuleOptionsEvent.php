<?php
/**
 * @link  https://github.com/nnx-company/module-options
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\ModuleOptions;

use Zend\EventManager\Event;

/**
 * Class ModuleOptionsEvent
 *
 * @package Nnx\ModuleOptions
 */
class ModuleOptionsEvent extends Event implements ModuleOptionsEventInterface
{

    /**
     * Имя класса модуля
     *
     * @var string
     */
    protected $moduleClassName;

    /**
     * @inheritdoc
     *
     * @return string
     */
    public function getModuleClassName()
    {
        return $this->moduleClassName;
    }

    /**
     * @inheritdoc
     *
     * @param string $moduleClassName
     *
     * @return $this
     */
    public function setModuleClassName($moduleClassName)
    {
        $this->moduleClassName = $moduleClassName;

        return $this;
    }
}
