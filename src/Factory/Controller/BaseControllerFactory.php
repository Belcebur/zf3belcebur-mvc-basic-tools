<?php

namespace ZF3Belcebur\MvcBasicTools\Factory\Controller;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\Mvc\I18n\Translator;
use Zend\ServiceManager\Factory\FactoryInterface;
use ZF3Belcebur\MvcBasicTools\Controller\BaseController;

class BaseControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     *
     * @return BaseController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new $requestedName(
            $container->get(EntityManager::class),
            $container->get(Translator::class),
            $container->get('Router')
        );
    }
}
