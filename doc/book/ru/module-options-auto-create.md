# Автоматическое создание ModuleOptions, с помощью абстрактной фабрики \Nnx\ModuleOptions\ModuleConfigKeyProviderInterface

## Быстрый старт

В корне проекта создать класс отвечающй за описание настроек модуля

- По умолчанию класс должен распологаться в src\Options\ModuleOptions.php (**Месторасположение по умолачнаию можно изменить через настройки @see \Nnx\ModuleOptions\Options\ModuleOptions::$moduleOptionsClassNameSuffix)
- ModuleOptions должен:
    - Имплементировать интерфейс \Nnx\ModuleOptions\ModuleOptionsInterface
    - Класс должен наследоваться от \Zend\Stdlib\AbstractOptions
- Класс модуля должен имплементировать \Nnx\ModuleOptions\ModuleConfigKeyProviderInterface
- В классе модуля реализовать метод getModuleConfigKey возвращающий имя ключа, по которому в конфигах приложения, можно получить массив с данными, для настроек модуля

### Пример ModuleOptions:

- Расположение:

```txt
src
    Options
        ModuleOptions
```

- Пример релизации ModuleOptions

```php

use Nnx\ModuleOptions\ModuleOptionsInterface;
use Zend\Stdlib\AbstractOptions;

/**
 * Class ModuleOptions
 *
 * @package Nnx\ModuleOptions\PhpUnit\TestData\DefaultModuleOptionsAbstractFactory\TestModule1\Options
 */
class ModuleOptions extends AbstractOptions implements ModuleOptionsInterface
{

}

```
### Пример Module:

```php

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Nnx\ModuleOptions\ModuleConfigKeyProviderInterface;

/**
 * Class Module
 *
 */
class Module implements
    AutoloaderProviderInterface,
    ConfigProviderInterface,
    ModuleConfigKeyProviderInterface
{
    /**
     * Имя секции в конфиги приложения отвечающей за настройки модуля
     *
     * @var string
     */
    const CONFIG_KEY = 'test_module_1';

    /**
     * @return string
     */
    public function getModuleConfigKey()
    {
        return static::CONFIG_KEY;
    }

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

    /**
     * @inheritdoc
     *
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
} 


```

# Описание алгоритма работы \Nnx\ModuleOptions\DefaultModuleOptionsAbstractFactory

Абстрактная фабрика \Nnx\ModuleOptions\DefaultModuleOptionsAbstractFactory:

- Определяет по имени класса ModuleOptions, к какому модулю относится данный класс
- Определяет имплементирует ли модуль интерфейс \Nnx\ModuleOptions\ModuleConfigKeyProviderInterface, если да, то модуль может вернуть ключ, по которому из конфига приложения, можно поулчить настройки модуля
- Создает ModuleOptions, передавая ему конфиги модуля, в качестве аргумента конструктора