<?php
/**
 * @link  https://github.com/nnx-framework/module-options
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\ModuleOptions\PhpUnit\TestData\OptionsByModuleTest\TestModule2;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;


/**
 * Class Module
 *
 * @package Nnx\ModuleOptions\PhpUnit\TestData\OptionsByModuleTest\TestModule2
 */
class Module implements
    AutoloaderProviderInterface
{

    /**
     * Имя модуля
     *
     * @var string
     */
    const MODULE_NAME = __NAMESPACE__;

    /**
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/',
                ),
            ),
        );
    }

} 