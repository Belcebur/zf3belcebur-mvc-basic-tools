<?php
/**
 * Created by PhpStorm.
 * User: Deibi
 * Date: 01/10/2018
 * Time: 19:25
 */

namespace ZF3Belcebur\MvcBasicTools\Controller;


use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\Mvc\I18n\Router\TranslatorAwareTreeRouteStack;
use Zend\Mvc\I18n\Translator as MvcTranslator;
use Zend\Router\Http\TreeRouteStack;
use Zend\Router\RouteStackInterface;
use Zend\Router\SimpleRouteStack;
use ZF3Belcebur\MvcBasicTools\ControllerTrait\BasicControllerTrait;
use function defined;

/**
 * Class BaseRestfulController
 * @package ZF3Belcebur\MvcBasicTools\Controller
 */
abstract class BaseRestfulController extends AbstractRestfulController
{

    use BasicControllerTrait;

    /**
     * BaseController constructor.
     * @param EntityManager $entityManager
     * @param MvcTranslator $mvcTranslator
     * @param TranslatorAwareTreeRouteStack|TreeRouteStack|SimpleRouteStack|RouteStackInterface $router
     */
    public function __construct(EntityManager $entityManager, MvcTranslator $mvcTranslator, RouteStackInterface $router)
    {
        $this->limitItemsPerPage = defined('DEFAULT_LIMIT_ITEMS_PER_PAGE') ? DEFAULT_LIMIT_ITEMS_PER_PAGE : 50;
        $this->entityManager = $entityManager;
        $this->mvcTranslator = $mvcTranslator;
        $this->router = $router;
        $this->translator = $mvcTranslator->getTranslator();
    }

}
