<?php

namespace ZF3Belcebur\MvcBasicTools\Factory\Controller;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\Mvc\I18n\Translator as MvcTranslator;
use Zend\ServiceManager\Factory\FactoryInterface;
use ZF3Belcebur\MvcBasicTools\Controller\FormManagerController;

class FormManagerControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     *
     * @return FormManagerController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new $requestedName(
            $container->get('FormElementManager'),
            $container->get(EntityManager::class),
            $container->get(MvcTranslator::class),
            $container->get('Router')
        );
    }
}
