<?php
/**
 * @link  https://github.com/nnx-framework/module-options
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\ModuleOptions;

use Zend\ModuleManager\ModuleManagerInterface;
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
        /** @var ModuleManagerInterface $moduleManager */
        $moduleManager = $serviceLocator->get('moduleManager');

        return new ModuleOptionsPluginManager($moduleManager);
    }
}
