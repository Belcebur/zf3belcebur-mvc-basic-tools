<?php

namespace ZF3Belcebur\MvcBasicTools\Factory\View\Helper;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use ZF3Belcebur\MvcBasicTools\View\Helper\BTools;

class ToolsViewHelperFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     *
     * @return BTools
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new BTools(
            $container->get('Router'),
            $container->get('Request'),
            $container->get('FormElementManager')
        );
    }
}
