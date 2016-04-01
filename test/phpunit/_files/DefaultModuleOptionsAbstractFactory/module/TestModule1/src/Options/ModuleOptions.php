<?php
/**
 * @link  https://github.com/nnx-framework/module-options
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\ModuleOptions\PhpUnit\TestData\DefaultModuleOptionsAbstractFactory\TestModule1\Options;

use Nnx\ModuleOptions\ModuleOptionsInterface;
use Zend\Stdlib\AbstractOptions;

/**
 * Class ModuleOptions
 *
 * @package Nnx\ModuleOptions\PhpUnit\TestData\DefaultModuleOptionsAbstractFactory\TestModule1\Options
 */
class ModuleOptions extends AbstractOptions implements ModuleOptionsInterface
{
    /**
     * @var string
     */
    protected $validKey;

    /**
     * @return string
     */
    public function getValidKey()
    {
        return $this->validKey;
    }

    /**
     * @param string $validKey
     *
     * @return $this
     */
    public function setValidKey($validKey)
    {
        $this->validKey = $validKey;

        return $this;
    }

}