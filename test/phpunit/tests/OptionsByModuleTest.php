<?php
/**
 * @link  https://github.com/nnx-framework/module-options
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
use Nnx\ModuleOptions\PhpUnit\TestData\OptionsByModuleTest as TestApp;


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
         [FooTestService1::class, TestApp\TestModule1\Module::MODULE_NAME . '\\'],
         [FooTestService2::class, TestApp\TestModule1\Module::MODULE_NAME . '\\'],
         [BarTestService1::class, TestApp\TestModule2\Module::MODULE_NAME . '\\'],
         [BarTestService2::class, TestApp\TestModule2\Module::MODULE_NAME . '\\'],
     ];

    /**
     * Данные для кейса, когда проверятся можно ли по имени класса получить, имя модуля
     *
     * @var array
     */
    protected static $hasModuleNameByClassNameTestData = [
        [FooTestService1::class, true],
        [FooTestService2::class, true],
        [BarTestService1::class, true],
        [BarTestService2::class, true],
    ];

    /**
     * Данные для кейса, когда проверятся получения настроек модуля по имени модуля
     *
     * @var array
     */
    protected static $moduleNameToOptionModuleClassName = [
        [TestApp\TestModule1\Module::MODULE_NAME, TestModuleOptions1::class],
        [TestApp\TestModule2\Module::MODULE_NAME, TestModuleOptions2::class],
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
     * Возвращает данные для кейса, когда проверятся можно ли по имени класса получить, имя модуля
     *
     * @return array
     */
    public function getHasModuleNameByClassNameTestData()
    {
        return static::$hasModuleNameByClassNameTestData;
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
     *
     * @throws \Zend\Stdlib\Exception\LogicException
     * @throws \Zend\ServiceManager\ServiceManager
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \PHPUnit_Framework_AssertionFailedError
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
     *
     * @throws \Zend\Stdlib\Exception\LogicException
     * @throws \Zend\ServiceManager\ServiceManager
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
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
     * @dataProvider getHasModuleNameByClassNameTestData
     *
     * @param string $className
     * @param boolean $expected
     *
     * @throws \Zend\Stdlib\Exception\LogicException
     * @throws \Zend\ServiceManager\ServiceManager
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testHasModuleNameByClassName($className, $expected)
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getPathToOptionsByModuleTestAppConfig()
        );


        /** @var ModuleOptionsPluginManagerInterface $moduleOptionsManager */
        $moduleOptionsManager = $this->getApplicationServiceLocator()->get(ModuleOptionsPluginManagerInterface::class);

        static::assertInstanceOf(ModuleOptionsPluginManagerInterface::class, $moduleOptionsManager);

        $actual = $moduleOptionsManager->hasModuleNameByClassName($className);

        static::assertEquals($expected, $actual);
    }




    /**
     * @dataProvider getModuleNameToOptionModuleClassNameData
     *
     * @param string $moduleName
     * @param string $expectedModuleOptionsClassName
     * @throws \Zend\Stdlib\Exception\LogicException
     * @throws \Zend\ServiceManager\ServiceManager
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
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
     *
     * @throws \Zend\Stdlib\Exception\LogicException
     * @throws \Zend\ServiceManager\ServiceManager
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
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
