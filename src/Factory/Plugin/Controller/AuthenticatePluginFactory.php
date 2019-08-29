<?php

namespace ZF3Belcebur\MvcBasicTools\Factory\Plugin\Controller;

use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;
use ZF3Belcebur\MvcBasicTools\Controller\Plugin\AuthenticatePlugin;

class AuthenticatePluginFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     *
     * @return AuthenticatePlugin
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new AuthenticatePlugin(
            $container->get(AuthenticationService::class)
        );
    }
}
