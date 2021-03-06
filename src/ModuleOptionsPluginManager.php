<?php
/**
 * @link  https://github.com/nnx-framework/module-options
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\ModuleOptions;

use Zend\EventManager\EventManagerAwareTrait;
use Zend\ServiceManager\AbstractPluginManager;
use Nnx\ModuleOptions\Options\ModuleOptions;
use Zend\EventManager\EventInterface;
use ReflectionClass;
use Zend\ServiceManager\ConfigInterface;
use Zend\ModuleManager\ModuleManagerInterface;

/**
 * Class ModuleOptionsPluginManager
 *
 * @package Nnx\ModuleOptions
 *
 */
class ModuleOptionsPluginManager extends AbstractPluginManager implements ModuleOptionsPluginManagerInterface
{
    use EventManagerAwareTrait;

    /**
     * Имя секции в конфиги приложения отвечающей за настройки менеджера
     *
     * @var string
     */
    const CONFIG_KEY = 'module_options';

    /**
     * Прототип для создания нового объекта события
     *
     * @var ModuleOptionsEventInterface
     */
    protected $eventPrototype;

    /**
     * Кеш. Ключем является имя модуля, а значением объект с настройками модуля
     *
     * @var array
     */
    protected $moduleNameToModuleOptions = [];

    /**
     * Ключем является имя класса, а значением имя модуля, к которому принадлежит этот класс
     *
     * @var array
     */
    protected $classNameToModuleName = [];


    /**
     * Менеджер модулей
     *
     * @var ModuleManagerInterface
     */
    protected $moduleManager;

    /**
     * Индекс по именам модулей. Ключем является имя модуля. Значением колличество символов в имени модуля. Данные отсортированы
     * по возрастанию длинны имени модуля
     *
     * @var array|null
     */
    protected $modulesIndex;

    /**
     * Имена модулей, по формату совпадающие с именами испольуземыми в \Zend\ModuleManager\ModuleManager
     *
     * @var array
     */
    protected $normalizeModuleNameByClassName = [];

    /**
     * ModuleOptionsPluginManager constructor.
     *
     * @param ModuleManagerInterface $moduleManager
     * @param ConfigInterface|null   $configuration
     */
    public function __construct(ModuleManagerInterface $moduleManager, ConfigInterface $configuration = null)
    {
        $this->setModuleManager($moduleManager);
        parent::__construct($configuration);
    }

    /**
     * Возвращает индекс по именам модулей.
     *
     *
     * @return array
     */
    public function getModulesIndex()
    {
        if ($this->modulesIndex) {
            return $this->modulesIndex;
        }

        $modules = $this->getModuleManager()->getModules();

        $modulesIndex = [];

        foreach ($modules as $moduleName) {
            $prepareModuleName = rtrim($moduleName, '\\') . '\\';
            $modulesIndex[$prepareModuleName] = strlen($prepareModuleName);
        }

        arsort($modulesIndex, SORT_NUMERIC);
        $this->modulesIndex = $modulesIndex;

        return $this->modulesIndex;
    }


    /**
     * @inheritdoc
     *
     * @param string $className
     *
     * @return ModuleOptionsInterface
     *
     * @throws Exception\ErrorGetModuleOptionsByClassNameException
     */
    public function getOptionsByClassName($className)
    {
        try {
            $moduleName = $this->getModuleNameByClassName($className);
            $moduleOptions = $this->getOptionsByModuleName($moduleName);
        } catch (\Exception $e) {
            throw new Exception\ErrorGetModuleOptionsByClassNameException($e->getMessage(), $e->getCode(), $e);
        }
        return $moduleOptions;
    }

    /**
     * @inheritdoc
     *
     * @param string $className
     *
     * @return boolean
     *
     * @throws Exception\ResolveModuleNameException
     * @throws Exception\InvalidEventException
     */
    public function hasOptionsByClassName($className)
    {
        if (!$this->hasModuleNameByClassName($className)) {
            return false;
        }

        $moduleName = $this->getModuleNameByClassName($className);

        return $this->hasOptionsByModuleName($moduleName);
    }


    /**
     * @inheritdoc
     *
     * @param $className
     *
     * @return string
     *
     * @throws Exception\ResolveModuleNameException
     */
    public function getModuleNameByClassName($className)
    {
        $resultModuleName = $this->autoDetectModuleNameByClassName($className);
        if (null === $resultModuleName) {
            $errMsg = sprintf('Unable to determine the module name for a class of %s', $className);
            throw new Exception\ResolveModuleNameException($errMsg);
        }

        return $resultModuleName;
    }

    /**
     * @inheritdoc
     *
     * @param $className
     *
     * @return string
     *
     * @throws Exception\ResolveModuleNameException
     */
    public function getNormalizeModuleNameByClassName($className)
    {
        if (!array_key_exists($className, $this->normalizeModuleNameByClassName)) {
            $moduleName = $this->getModuleNameByClassName($className);
            $this->normalizeModuleNameByClassName[$className] = rtrim($moduleName, '\\');
        }

        return $this->normalizeModuleNameByClassName[$className];
    }

    /**
     * Автоматиченское определение имени модуля, по имени любого класса входящего в этот модуль. В случае если определить
     * не удалось, возвращается null
     *
     * @param $className
     *
     * @return string
     */
    public function autoDetectModuleNameByClassName($className)
    {
        if (array_key_exists($className, $this->classNameToModuleName)) {
            $resultModuleName =  $this->classNameToModuleName[$className];
        } else {
            $index = $this->getModulesIndex();
            $classNameLength = strlen($className);

            $resultModuleName = null;
            foreach ($index as $moduleName => $moduleNameLength) {
                if ($classNameLength > $moduleNameLength && 0 === strrpos($className, $moduleName)) {
                    $resultModuleName = $moduleName;
                    break;
                }
            }
            $this->classNameToModuleName[$className] = $resultModuleName;
        }

        return $resultModuleName;
    }

    /**
     * @inheritdoc
     *
     * @param $className
     *
     * @return boolean
     */
    public function hasModuleNameByClassName($className)
    {
        $resultModuleName = $this->autoDetectModuleNameByClassName($className);

        return null !== $resultModuleName;
    }

    /**
     * @inheritdoc
     *
     * @param string $moduleName
     *
     * @throws Exception\InvalidEventException
     * @throws Exception\RuntimeException
     * @throws \Zend\ServiceManager\Exception\RuntimeException
     *
     * @return ModuleOptionsInterface
     */
    public function getOptionsByModuleName($moduleName)
    {
        $moduleOptions = $this->resolveModuleOptionsByModuleName($moduleName);
        $this->validatePlugin($moduleOptions);

        return $moduleOptions;
    }

    /**
     * Попытка получить объект ModuleOptions, на основе имени модуля. В случае если такой возможности нет, возвращается null
     *
     * @param string $moduleName
     *
     * @return mixed|null
     *
     * @throws Exception\InvalidEventException
     */
    public function resolveModuleOptionsByModuleName($moduleName)
    {
        if (array_key_exists($moduleName, $this->moduleNameToModuleOptions)) {
            $moduleOptions = $this->moduleNameToModuleOptions[$moduleName];
        } else {
            $event = $this->eventFactory();
            $event->setName(ModuleOptionsEventInterface::LOAD_MODULE_OPTIONS_EVENT);
            $event->setTarget($this);
            $event->setModuleName($moduleName);

            $eventCollections = $this->getEventManager()->trigger($event, function ($result) {
                return $result instanceof ModuleOptionsInterface;
            });

            $moduleOptionsResult = $eventCollections->last();

            $moduleOptions = $this->isValidModuleOptions($moduleOptionsResult) ? $moduleOptionsResult : null;
            $this->moduleNameToModuleOptions[$moduleName] = $moduleOptions;
        }

        return $moduleOptions;
    }

    /**
     * @inheritdoc
     *
     * @param string $moduleName
     *
     * @return boolean
     * @throws Exception\InvalidEventException
     */
    public function hasOptionsByModuleName($moduleName)
    {
        $moduleOptions = $this->resolveModuleOptionsByModuleName($moduleName);

        return null !== $moduleOptions;
    }


    /**
     * Фабрика для создания события
     *
     * @return ModuleOptionsEventInterface
     *
     * @throws Exception\InvalidEventException
     */
    public function eventFactory()
    {
        if (null !== $this->eventPrototype) {
            return clone $this->eventPrototype;
        }

        try {
            /** @var ModuleOptions $moduleOptions */
            $moduleOptions = $this->get(ModuleOptions::class);

            $moduleOptionsEventClassName = $moduleOptions->getModuleOptionsEventClassName();
            $r = new ReflectionClass($moduleOptionsEventClassName);

            $eventPrototype = $r->newInstance();
            if (!$eventPrototype instanceof EventInterface) {
                $errMsg = sprintf(
                    'Event of type %s is invalid; must implement %s',
                    (is_object($eventPrototype) ? get_class($eventPrototype) : gettype($eventPrototype)),
                    ModuleOptionsEventInterface::class
                );
                throw new Exception\RuntimeException($errMsg);
            }
            $this->eventPrototype = $eventPrototype;
        } catch (\Exception $e) {
            throw new Exception\InvalidEventException($e->getMessage(), $e->getCode(), $e);
        }

        return clone $this->eventPrototype;
    }

    /**
     * @inheritdoc
     */
    public function attachDefaultListeners()
    {
        $this->getEventManager()->attach(ModuleOptionsEventInterface::LOAD_MODULE_OPTIONS_EVENT, [$this, 'loadModuleOptions'], 100);
    }

    /**
     * Получение конфига модуля, по имени класса модуля
     *
     * @param ModuleOptionsEventInterface $e
     *
     * @return ModuleOptionsInterface|null
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Zend\ServiceManager\Exception\ServiceNotCreatedException
     * @throws \Zend\ServiceManager\Exception\RuntimeException
     */
    public function loadModuleOptions(ModuleOptionsEventInterface $e)
    {
        $moduleName = $e->getModuleName();

        /** @var ModuleOptions $moduleOptions */
        $moduleOptions = $this->get(ModuleOptions::class);

        $suffix = $moduleOptions->getModuleOptionsClassNameSuffix();

        $prepareModuleNamespace = rtrim($moduleName, '\\');
        $prepareSuffix = ltrim($suffix, '\\');

        $moduleOptionsServiceName = $prepareModuleNamespace . '\\' . $prepareSuffix;

        if (!$this->has($moduleOptionsServiceName)) {
            return null;
        }

        return $this->get($moduleOptionsServiceName);
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception\RuntimeException
     */
    public function validatePlugin($plugin)
    {
        if ($this->isValidModuleOptions($plugin)) {
            return;
        }

        throw new Exception\RuntimeException(sprintf(
            'Plugin of type %s is invalid; must implement %s',
            (is_object($plugin) ? get_class($plugin) : gettype($plugin)),
            ModuleOptionsInterface::class
        ));
    }

    /**
     * Проверяет что переданный объект является валидным экземпляром ModuleOptions
     *
     * @param $plugin
     *
     * @return boolean
     */
    public function isValidModuleOptions($plugin)
    {
        return $plugin instanceof ModuleOptionsInterface;
    }

    /**
     * Возвращает менеджер модулей
     *
     * @return ModuleManagerInterface
     */
    public function getModuleManager()
    {
        return $this->moduleManager;
    }

    /**
     * Устанавливает менеджер модулей
     *
     * @param ModuleManagerInterface $moduleManager
     *
     * @return $this
     */
    public function setModuleManager(ModuleManagerInterface $moduleManager)
    {
        $this->moduleManager = $moduleManager;

        return $this;
    }
}
