<?php

namespace ZF3Belcebur\MvcBasicTools\Controller\Plugin;

use Doctrine\ORM\ORMInvalidArgumentException;
use DoctrineModule\Authentication\Adapter\ObjectRepository;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Result as AuthenticationResult;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\ServiceManager\Exception\ServiceNotFoundException;


class AuthenticatePlugin extends AbstractPlugin
{
    protected $authenticationService;

    /**
     * Constructor.
     * @param AuthenticationService $authenticationService
     */
    public function __construct(AuthenticationService $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }


    /**
     * @param string $identity
     * @param string $credential
     *
     * @return AuthenticationResult
     * @throws ORMInvalidArgumentException
     * @throws ServiceNotFoundException
     */
    public function authenticate($identity, $credential): AuthenticationResult
    {
        /**
         * @var AuthenticationResult $result
         */

        /** @var  ObjectRepository $adapter */
        $adapter = $this->authenticationService->getAdapter();
        $adapter->setIdentity($identity);
        $adapter->setCredential($credential);

        $result = $this->authenticationService->authenticate($adapter);
        if ($result->getCode() === AuthenticationResult::SUCCESS) {
            $this->authenticationService->getStorage()->write($result->getIdentity());
        }

        return $result;
    }

    public function logout(): void
    {
        $this->authenticationService->clearIdentity();
    }
}
