<?php

namespace ZF3Belcebur\MvcBasicTools\Factory\View\Helper;

use Interop\Container\ContainerInterface;
use Zend\Mvc\Controller\PluginManager;
use Zend\ServiceManager\Factory\FactoryInterface;
use ZF3Belcebur\MvcBasicTools\View\Helper\BTools;

class BToolsFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     *
     * @return BTools return BTools or your extended class
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new $requestedName(
            $container->get('MvcTranslator'),
            $container->get('Router'),
            $container->get('Request'),
            $container->get('FormElementManager'),
            $container->get(PluginManager::class)
        );
    }
}
