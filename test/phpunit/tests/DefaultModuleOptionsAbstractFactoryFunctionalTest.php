<?php
/**
 * @link  https://github.com/nnx-framework/module-options
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\ModuleOptions\PhpUnit\Test;

use Nnx\ModuleOptions\PhpUnit\TestData\TestPaths;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Nnx\ModuleOptions\ModuleOptionsPluginManagerInterface;
use Nnx\ModuleOptions\PhpUnit\TestData\DefaultModuleOptionsAbstractFactory as App;

/**
 * Class DefaultModuleOptionsAbstractFactoryFunctionalTest
 *
 * @package Nnx\ModuleOptions\PhpUnit\Test
 */
class DefaultModuleOptionsAbstractFactoryFunctionalTest extends AbstractHttpControllerTestCase
{

    /**
     * Ключем является имя класса ModuleOptions, а значением ключ полученный из опций модуля
     *
     * @return array
     */
    public function dataModuleOptionsClassNameAndValidKey()
    {
        return [
            [App\TestModule1\Options\ModuleOptions::class, 'valid_key_module_1'],
            [App\TestModule2\Options\ModuleOptions::class, 'valid_key_module_2'],
            [App\TestModule3\Options\ModuleOptions::class, 'valid_key_module_3'],
        ];
    }


    /**
     * @dataProvider dataModuleOptionsClassNameAndValidKey
     *
     * @param $moduleOptionsClassName
     * @param $validKey
     *
     * @throws \Zend\Stdlib\Exception\LogicException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testModuleOptions($moduleOptionsClassName, $validKey)
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getPathToDefaultModuleOptionsAbstractFactoryAppConfig()
        );

        /** @var ModuleOptionsPluginManagerInterface $moduleOptionsManager */
        $moduleOptionsManager = $this->getApplicationServiceLocator()->get(ModuleOptionsPluginManagerInterface::class);

        $moduleOptions = $moduleOptionsManager->get($moduleOptionsClassName);

        static::assertInstanceOf($moduleOptionsClassName, $moduleOptions);
        static::assertEquals($validKey, call_user_func([$moduleOptions, 'getValidKey']));
    }
}
