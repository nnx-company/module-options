<?php
/**
 * @link  https://github.com/nnx-company/module-options
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\ModuleOptions;

use Zend\EventManager\EventManagerAwareTrait;
use Zend\ServiceManager\AbstractPluginManager;
use Nnx\ModuleOptions\Options\ModuleOptions;
use Zend\EventManager\EventInterface;
use ReflectionClass;


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
     * Кеш полученных опций модуля
     *
     * @var array
     */
    protected $moduleOptions = [];

    /**
     * Прототип для создания нового объекта события
     *
     * @var ModuleOptionsEventInterface
     */
    protected $eventPrototype;


    /**
     * Возвращает конфиг модуля, по имиени класса модуля
     *
     * @param string $moduleClassName
     *
     * @return ModuleOptionsInterface
     *
     * @throws Exception\InvalidEventException
     * @throws Exception\RuntimeException
     * @throws \Zend\ServiceManager\Exception\RuntimeException
     */
    public function getOptionsByModule($moduleClassName)
    {
        if (array_key_exists($moduleClassName, $this->moduleOptions)) {
            return $this->moduleOptions[$moduleClassName];
        }


        $event = $this->eventFactory();
        $event->setName(ModuleOptionsEventInterface::LOAD_MODULE_OPTIONS_EVENT);
        $event->setTarget($moduleClassName);
        $event->setModuleClassName($moduleClassName);

        $eventCollections = $this->getEventManager()->trigger($event, function ($result) {
            return $result instanceof ModuleOptionsInterface;
        });

        $moduleOptions = $eventCollections->last();

        $this->validatePlugin($moduleOptions);

        return $moduleOptions;
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
        $moduleClassName = $e->getModuleClassName();

        $pos = strrpos($moduleClassName, '\\Module');

        $expectedPos = strlen($moduleClassName) - 7;

        if ($pos !== $expectedPos) {
            return null;
        }

        $moduleNamespace = substr($moduleClassName, 0, $expectedPos);

        /** @var ModuleOptions $moduleOptions */
        $moduleOptions = $this->get(ModuleOptions::class);

        $suffix = $moduleOptions->getModuleOptionsClassNameSuffix();

        $prepareModuleNamespace = rtrim($moduleNamespace, '//');
        $prepareSuffix = ltrim($suffix, '//');

        $moduleOptionsServiceName = $prepareModuleNamespace . '//' . $prepareSuffix;

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
        if ($plugin instanceof ModuleOptionsInterface) {
            return;
        }

        throw new Exception\RuntimeException(sprintf(
            'Plugin of type %s is invalid; must implement %s',
            (is_object($plugin) ? get_class($plugin) : gettype($plugin)),
            ModuleOptionsInterface::class
        ));
    }
}
