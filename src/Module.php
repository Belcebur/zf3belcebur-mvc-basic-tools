<?php

namespace ZF3Belcebur\MvcBasicTools;

use Zend\Authentication\AuthenticationService;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ControllerPluginProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ModuleManager\Feature\ViewHelperProviderInterface;
use Zend\ServiceManager\Config;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZF3Belcebur\MvcBasicTools\Controller\Plugin\AuthenticatePlugin;
use ZF3Belcebur\MvcBasicTools\Factory\Plugin\Controller\AuthenticatePluginFactory;
use ZF3Belcebur\MvcBasicTools\Factory\View\Helper\BToolsFactory;
use ZF3Belcebur\MvcBasicTools\View\Helper\BTools;

class Module implements ConfigProviderInterface, ViewHelperProviderInterface, ControllerPluginProviderInterface, ServiceProviderInterface
{
    public const CONFIG_KEY = __NAMESPACE__;

    public function getConfig(): array
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * Expected to return \Zend\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array|Config
     */
    public function getViewHelperConfig(): array
    {
        return [
            'factories' => [
                BTools::class => BToolsFactory::class,
            ],
            'aliases' => [
                'bTools' => BTools::class,
            ],
        ];
    }

    /**
     * Expected to return \Zend\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array|Config
     */
    public function getControllerPluginConfig(): array
    {
        return [
            'factories' => [
                AuthenticatePlugin::class => AuthenticatePluginFactory::class,
            ],
            'aliases' => [
                'authenticatePlugin' => AuthenticatePlugin::class,
            ],
        ];
    }

    /**
     * Expected to return \Zend\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array|Config
     */
    public function getServiceConfig(): array
    {
        return [
            'factories' => [
                AuthenticationService::class => static function (ServiceLocatorInterface $serviceManager) {
                    return $serviceManager->get('doctrine.authenticationservice.orm_default');
                },
            ],
        ];
    }
}
