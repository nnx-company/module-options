<?php
/**
 * @link  https://github.com/nnx-company/module-options
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\ModuleOptions\Options;

use Nnx\ModuleOptions\Module;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


/**
 * Class ModuleOptionsFactory
 *
 * @package Nnx\ModuleOptions\Options
 */
class ModuleOptionsFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return ModuleOptions
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $appServiceLocator = $serviceLocator instanceof AbstractPluginManager ? $serviceLocator->getServiceLocator() : $serviceLocator;

        /** @var array $appConfig */
        $appConfig = $appServiceLocator->get('config');
        $moduleConfig = array_key_exists(Module::CONFIG_KEY, $appConfig) ? $appConfig[Module::CONFIG_KEY] : [];

        return new ModuleOptions($moduleConfig);
    }
}
