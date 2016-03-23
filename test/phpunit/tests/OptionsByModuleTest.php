<?php
/**
 * @link  https://github.com/nnx-company/module-options
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\ModuleOptions\PhpUnit\Test;

use Nnx\ModuleOptions\PhpUnit\TestData\TestPaths;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Nnx\ModuleOptions\ModuleOptionsPluginManagerInterface;
use Nnx\ModuleOptions\PhpUnit\TestData\OptionsByModuleTest\TestModule1\TestService\FooTestService1;
use Nnx\ModuleOptions\PhpUnit\TestData\OptionsByModuleTest\TestModule1\TestService\FooTestService2;
use Nnx\ModuleOptions\PhpUnit\TestData\OptionsByModuleTest\TestModule1\Options\ModuleOptions as TestModuleOptions1;
use Nnx\ModuleOptions\PhpUnit\TestData\OptionsByModuleTest\TestModule2\TestService\BarTestService1;
use Nnx\ModuleOptions\PhpUnit\TestData\OptionsByModuleTest\TestModule2\TestService\BarTestService2;
use Nnx\ModuleOptions\PhpUnit\TestData\OptionsByModuleTest\TestModule2\Options\ModuleOptions as TestModuleOptions2;
use Nnx\ModuleOptions\PhpUnit\TestData\OptionsByModuleTest\TestModule3\Module as TestModule3;

/**
 * Class ModuleTest
 *
 * @package Nnx\ModuleOptions\PhpUnit\Test
 */
class OptionsByModuleTest extends AbstractHttpControllerTestCase
{

    /**
     * Данные для кейса, когда проверятся получения настроек модуля, по имени класса из данного модуля
     *
     * @var array
     */
    protected static $classNameToOptionsClassName = [
        [FooTestService1::class, TestModuleOptions1::class],
        [FooTestService2::class, TestModuleOptions1::class],
        [BarTestService1::class, TestModuleOptions2::class],
        [BarTestService2::class, TestModuleOptions2::class],
    ];

    /**
     * Данные для кейса, когда проверятся получения имени модуля, по имени класса из данного модуля
     *
     * @var array
     */
     protected static $classNameToModuleName = [
         [FooTestService1::class, 'Nnx\\ModuleOptions\\PhpUnit\\TestData\\OptionsByModuleTest\\TestModule1\\'],
         [FooTestService2::class, 'Nnx\\ModuleOptions\\PhpUnit\\TestData\\OptionsByModuleTest\\TestModule1\\'],
         [BarTestService1::class, 'Nnx\\ModuleOptions\\PhpUnit\\TestData\\OptionsByModuleTest\\TestModule2\\'],
         [BarTestService2::class, 'Nnx\\ModuleOptions\\PhpUnit\\TestData\\OptionsByModuleTest\\TestModule2\\'],
     ];

    /**
     * Данные для кейса, когда проверятся получения настроек модуля по имени модуля
     *
     * @var array
     */
    protected static $moduleNameToOptionModuleClassName = [
        ['Nnx\\ModuleOptions\\PhpUnit\\TestData\\OptionsByModuleTest\\TestModule1', TestModuleOptions1::class],
        ['Nnx\\ModuleOptions\\PhpUnit\\TestData\\OptionsByModuleTest\\TestModule2', TestModuleOptions2::class],
    ];

    /**
     * Возвращает данные для кейса, когда проверятся получения настроек модуля, по имени класса из данного модуля
     *
     * @return array
     */
    public function getClassNameToOptionsClassNameData()
    {
        return static::$classNameToOptionsClassName;
    }

    /**
     * Возвращает данные для кейса, когда проверятся получения настроек модуля, по имени класса из данного модуля
     *
     * @return array
     */
    public function getClassNameToModuleNameData()
    {
        return static::$classNameToModuleName;
    }


    /**
     * Данные для кейса, когда проверятся получения настроек модуля по имени модуля
     *
     * @return array
     */
    public function getModuleNameToOptionModuleClassNameData()
    {
        return static::$moduleNameToOptionModuleClassName;
    }

    /**
     * Проверка получения опций модуля, по имени класса который входит в данный модуль
     *
     * @dataProvider getClassNameToOptionsClassNameData
     *
     * @param string $className
     * @param string $moduleOptionsClassName
     */
    public function testGetOptionsByClassName($className, $moduleOptionsClassName)
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getPathToOptionsByModuleTestAppConfig()
        );

        /** @var ModuleOptionsPluginManagerInterface $moduleOptionsManager */
        $moduleOptionsManager = $this->getApplicationServiceLocator()->get(ModuleOptionsPluginManagerInterface::class);

        static::assertInstanceOf(ModuleOptionsPluginManagerInterface::class, $moduleOptionsManager);
        $options = $moduleOptionsManager->getOptionsByClassName($className);
        static::assertInstanceOf($moduleOptionsClassName, $options);

        $cacheOptions = $moduleOptionsManager->getOptionsByClassName($className);

        $validCache = $options === $cacheOptions;
        static::assertTrue($validCache);
    }

    /**
     * @dataProvider getClassNameToModuleNameData
     *
     * @param $className
     * @param $expectedModuleName
     */
    public function testGetModuleNameByClassName($className, $expectedModuleName)
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getPathToOptionsByModuleTestAppConfig()
        );


        /** @var ModuleOptionsPluginManagerInterface $moduleOptionsManager */
        $moduleOptionsManager = $this->getApplicationServiceLocator()->get(ModuleOptionsPluginManagerInterface::class);

        static::assertInstanceOf(ModuleOptionsPluginManagerInterface::class, $moduleOptionsManager);

        $actualClassName = $moduleOptionsManager->getModuleNameByClassName($className);

        static::assertEquals($expectedModuleName, $actualClassName);
    }


    /**
     * @dataProvider getModuleNameToOptionModuleClassNameData
     *
     * @param string $moduleName
     * @param string $expectedModuleOptionsClassName
     */
    public function testGetOptionsByModuleName($moduleName, $expectedModuleOptionsClassName)
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getPathToOptionsByModuleTestAppConfig()
        );


        /** @var ModuleOptionsPluginManagerInterface $moduleOptionsManager */
        $moduleOptionsManager = $this->getApplicationServiceLocator()->get(ModuleOptionsPluginManagerInterface::class);

        static::assertInstanceOf(ModuleOptionsPluginManagerInterface::class, $moduleOptionsManager);

        $moduleOptions = $moduleOptionsManager->getOptionsByModuleName($moduleName);

        static::assertInstanceOf($expectedModuleOptionsClassName, $moduleOptions);
    }



    /**
     * Проверка получения опций модуля, по его имени. Проврека когда объек настроек модуля не имплементирует заданный интерфейс
     *
     * @expectedException \Nnx\ModuleOptions\Exception\RuntimeException
     * @expectedExceptionMessage Plugin of type Nnx\ModuleOptions\PhpUnit\TestData\OptionsByModuleTest\TestModule3\Options\ModuleOptions is invalid; must implement Nnx\ModuleOptions\ModuleOptionsInterface
     */
    public function testGetInvalidModuleOptions()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getPathToOptionsByModuleTestAppConfig()
        );

        /** @var ModuleOptionsPluginManagerInterface $moduleOptionsManager */
        $moduleOptionsManager = $this->getApplicationServiceLocator()->get(ModuleOptionsPluginManagerInterface::class);

        $moduleOptionsManager->getOptionsByClassName(TestModule3::class);
    }
}
