<?php
/**
 * @link  https://github.com/nnx-framework/module-options
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\ModuleOptions;

use Zend\ModuleManager\ModuleManager;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ServiceLocatorInterface;
use ReflectionClass;
use Zend\Stdlib\AbstractOptions;


/**
 * Class DefaultModuleOptionsAbstractFactory
 *
 * @package Nnx\ModuleOptions
 */
class DefaultModuleOptionsAbstractFactory implements AbstractFactoryInterface
{

    /**
     * @var string
     */
    const MODULE_OPTIONS_CLASS_REFLECTION = 'moduleOptionsReflection';

    /**
     * @var string
     */
    const MODULE_OPTIONS_CONFIG_KEY = 'moduleOptionsConfigKey';

    /**
     * Ключем является имя ModuleOptions, а значение либо массив с данным для создания ModuleOptions, либо null
     *
     * @var array
     */
    protected $dataForModuleOptionsCreate = [];

    /**
     * @inheritdoc
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param                         $name
     * @param                         $requestedName
     *
     * @return boolean
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        if (!array_key_exists($requestedName, $this->dataForModuleOptionsCreate)) {
            if (!$serviceLocator instanceof ModuleOptionsPluginManagerInterface) {
                return false;
            }
            /** @var ModuleOptionsPluginManagerInterface $serviceLocator  */
            $this->buildDataForModuleOptionsCreate($serviceLocator, $requestedName);
        }

        return false !== $this->dataForModuleOptionsCreate[$requestedName];
    }

    /**
     * Возвращает массив содержащий данные необходимые для создания ModuleOptions
     *
     * @param ModuleOptionsPluginManagerInterface $moduleOptionsPluginManager
     * @param                                     $moduleOptionsClassName
     *
     * @return array
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function buildDataForModuleOptionsCreate(ModuleOptionsPluginManagerInterface $moduleOptionsPluginManager, $moduleOptionsClassName)
    {
        if (!class_exists($moduleOptionsClassName)) {
            $this->dataForModuleOptionsCreate[$moduleOptionsClassName] = false;
            return false;
        }

        $r = new ReflectionClass($moduleOptionsClassName);
        if (!$r->implementsInterface(ModuleOptionsInterface::class) || !$r->isSubclassOf(AbstractOptions::class)) {
            $this->dataForModuleOptionsCreate[$moduleOptionsClassName] = false;
            return false;
        }

        if (!$moduleOptionsPluginManager->hasModuleNameByClassName($moduleOptionsClassName)) {
            $this->dataForModuleOptionsCreate[$moduleOptionsClassName] = false;
            return false;
        }

        $moduleName = $moduleOptionsPluginManager->getNormalizeModuleNameByClassName($moduleOptionsClassName);

        if (!$moduleOptionsPluginManager instanceof AbstractPluginManager) {
            $this->dataForModuleOptionsCreate[$moduleOptionsClassName] = false;
            return false;
        }

        $appServiceLocator = $moduleOptionsPluginManager->getServiceLocator();

        /** @var ModuleManager $moduleManager */
        $moduleManager = $appServiceLocator->get('moduleManager');

        $loadedModules = $moduleManager->getLoadedModules();

        if (!array_key_exists($moduleName, $loadedModules)) {
            $this->dataForModuleOptionsCreate[$moduleOptionsClassName] = false;
            return false;
        }

        $module = $loadedModules[$moduleName];

        if (!$module instanceof ModuleConfigKeyProviderInterface) {
            $this->dataForModuleOptionsCreate[$moduleOptionsClassName] = false;
            return false;
        }

        $this->dataForModuleOptionsCreate[$moduleOptionsClassName] = [
            static::MODULE_OPTIONS_CLASS_REFLECTION => $r,
            static::MODULE_OPTIONS_CONFIG_KEY       => $module->getModuleConfigKey(),
        ];

        return $this->dataForModuleOptionsCreate[$moduleOptionsClassName];
    }




    /**
     * @inheritdoc
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param                         $name
     * @param                         $requestedName
     *
     * @return ModuleOptionsInterface
     *
     * @throws Exception\RuntimeException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        if (
            !array_key_exists($requestedName, $this->dataForModuleOptionsCreate)
            || !is_array($this->dataForModuleOptionsCreate[$requestedName])
            || !array_key_exists(static::MODULE_OPTIONS_CLASS_REFLECTION, $this->dataForModuleOptionsCreate[$requestedName])
            || !array_key_exists(static::MODULE_OPTIONS_CONFIG_KEY, $this->dataForModuleOptionsCreate[$requestedName])
            || !$this->dataForModuleOptionsCreate[$requestedName][static::MODULE_OPTIONS_CLASS_REFLECTION] instanceof ReflectionClass
        ) {
            $errMsg = 'Invalid data for ModuleOptions';
            throw new Exception\RuntimeException($errMsg);
        }

        $appServiceLocator = $serviceLocator;
        if ($serviceLocator instanceof AbstractPluginManager) {
            $appServiceLocator = $serviceLocator->getServiceLocator();
        }
        $appConfig = $appServiceLocator->get('Config');

        $configKey = $this->dataForModuleOptionsCreate[$requestedName][static::MODULE_OPTIONS_CONFIG_KEY];
        $moduleConfig = is_array($appConfig) && array_key_exists($configKey, $appConfig) ? $appConfig[$configKey] : [];

        /** @var ReflectionClass $r */
        $r = $this->dataForModuleOptionsCreate[$requestedName][static::MODULE_OPTIONS_CLASS_REFLECTION];
        $moduleConfig = $r->newInstance($moduleConfig);

        return $moduleConfig;
    }
}
