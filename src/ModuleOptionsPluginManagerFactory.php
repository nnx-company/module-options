<?php
/**
 * @link  https://github.com/nnx-company/module-options
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\ModuleOptions;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


/**
 * Class ModuleOptionsPluginManager
 *
 * @package Nnx\ModuleOptions
 *
 */
class ModuleOptionsPluginManagerFactory implements FactoryInterface
{
    /**
     * @inheritdoc
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return ModuleOptionsPluginManager
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new ModuleOptionsPluginManager();
    }
}
