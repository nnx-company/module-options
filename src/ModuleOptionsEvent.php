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
     * Имя модуля
     *
     * @var string
     */
    protected $moduleName;

    /**
     * @inheritdoc
     *
     * @return string
     */
    public function getModuleName()
    {
        return $this->moduleName;
    }

    /**
     * @inheritdoc
     *
     * @param string $moduleName
     *
     * @return $this
     */
    public function setModuleName($moduleName)
    {
        $this->moduleName = $moduleName;

        return $this;
    }
}
