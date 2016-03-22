<?php
/**
 * @link  https://github.com/nnx-company/module-options
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\ModuleOptions\PhpUnit\Test;

use Nnx\ModuleOptions\PhpUnit\TestData\TestPaths;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Nnx\ModuleOptions\ModuleOptionsPluginManagerInterface;
use Nnx\ModuleOptions\PhpUnit\TestData\OptionsByModuleTest\TestModule1\Module as TestModule1;
use Nnx\ModuleOptions\PhpUnit\TestData\OptionsByModuleTest\TestModule2\Module as TestModule2;
use Nnx\ModuleOptions\PhpUnit\TestData\OptionsByModuleTest\TestModule1\Options\ModuleOptions as TestModuleOptions1;

/**
 * Class ModuleTest
 *
 * @package Nnx\ModuleOptions\PhpUnit\Test
 */
class ModuleTest extends AbstractHttpControllerTestCase
{

    /**
     * Проверка получения опций модуля, по его имени.
     */
    public function testGetOptionsByModule()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getPathToOptionsByModuleTestAppConfig()
        );

        /** @var ModuleOptionsPluginManagerInterface $moduleOptionsManager */
        $moduleOptionsManager = $this->getApplicationServiceLocator()->get(ModuleOptionsPluginManagerInterface::class);

        static::assertInstanceOf(ModuleOptionsPluginManagerInterface::class, $moduleOptionsManager);
        $options = $moduleOptionsManager->getOptionsByModule(TestModule1::class);
        static::assertInstanceOf(TestModuleOptions1::class, $options);

        $cacheOptions = $moduleOptionsManager->getOptionsByModule(TestModule1::class);

        $validCache = $options === $cacheOptions;
        static::assertTrue($validCache);
    }

    /**
     * Проверка получения опций модуля, по его имени. Проврека когда объек настроек модуля не имплементирует заданный интерфейс
     *
     * @expectedException \Nnx\ModuleOptions\Exception\RuntimeException
     * @expectedExceptionMessage Plugin of type Nnx\ModuleOptions\PhpUnit\TestData\OptionsByModuleTest\TestModule2\Options\ModuleOptions is invalid; must implement Nnx\ModuleOptions\ModuleOptionsInterface
     */
    public function testGetInvalidModuleOptions()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getPathToOptionsByModuleTestAppConfig()
        );

        /** @var ModuleOptionsPluginManagerInterface $moduleOptionsManager */
        $moduleOptionsManager = $this->getApplicationServiceLocator()->get(ModuleOptionsPluginManagerInterface::class);

        $moduleOptionsManager->getOptionsByModule(TestModule2::class);
    }
}
