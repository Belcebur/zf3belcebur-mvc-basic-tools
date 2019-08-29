<?php
/**
 * Created by PhpStorm.
 * User: Deibi
 * Date: 01/10/2018
 * Time: 19:25
 */

namespace ZF3Belcebur\MvcBasicTools\Controller;


use Doctrine\ORM\EntityManager;
use Zend\Form\FormElementManager\FormElementManagerV3Polyfill as FormManager;
use Zend\Mvc\I18n\Router\TranslatorAwareTreeRouteStack;
use Zend\Mvc\I18n\Translator as MvcTranslator;
use Zend\Router\Http\TreeRouteStack;
use Zend\Router\RouteStackInterface;
use Zend\Router\SimpleRouteStack;

abstract class FormManagerController extends BaseController
{
    /**
     * @var FormManager
     */
    protected $formManager;

    /**
     * FormManagerController constructor.
     * @param FormManager $formManager
     * @param EntityManager $entityManager
     * @param MvcTranslator $mvcTranslator
     * @param TranslatorAwareTreeRouteStack|TreeRouteStack|SimpleRouteStack|RouteStackInterface $router
     */
    public function __construct(FormManager $formManager, EntityManager $entityManager, MvcTranslator $mvcTranslator, RouteStackInterface $router)
    {
        $this->formManager = $formManager;
        parent::__construct($entityManager, $mvcTranslator, $router);
    }

    /**
     * @return FormManager
     */
    public function getFormManager(): FormManager
    {
        return $this->formManager;
    }


}
