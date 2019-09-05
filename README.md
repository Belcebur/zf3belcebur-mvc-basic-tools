# zf3belcebur-mvc-basic-tools
Tools for views and controllers

## See
- [https://packagist.org/explore/?query=zf3belcebur](https://packagist.org/explore/?query=zf3belcebur)

## Installation

Installation of this module uses composer. For composer documentation, please refer to
[getcomposer.org](http://getcomposer.org/).

```sh
composer require zf3belcebur/mvc-basic-tools
```

Then add `ZF3Belcebur\MvcBasicTools` to your `config/application.config.php`

## Controller Plugin

- Integrate Doctrine ORM Auth with controllers
```php
/** @var \ZF3Belcebur\MvcBasicTools\Controller\Plugin\AuthenticatePlugin $authPlugin 
$authPlugin=$this->authenticatePlugin();
$authPlugin->authenticate('myCredential','myPassword'); // return \Zend\Authentication\Result
$authPlugin->logout(); // Clear session
```




## Abstract Controllers

Extends your controller with more functionalities

- `ZF3Belcebur\MVCBasicTools\BaseController`
- `ZF3Belcebur\MVCBasicTools\FormManagerController`

### `ZF3Belcebur\MVCBasicTools\BaseController` Properties

```php

    /** @var int defined from PHP CONSTANT "DEFAULT_LIMIT_ITEMS_PER_PAGE", if constant is not defined the value is 50 */
    protected $limitItemsPerPage;

    /** @var int defined from query param "page", by default 1*/
    protected $currentPageNumber;

    /** @var int defined from query param "limit", by default $this->limitItemsPerPage*/
    protected $itemCountPerPage;
```



### `ZF3Belcebur\MVCBasicTools\BaseController` Methods

- Create Paginator from Doctrine ORM QueryBuilder
```php
    /**
     * @param QueryBuilder $qb
     * @param bool $fetchJoinCollection
     * @param string|null $prefix
     * @param int $queryHydrationMode
     * @return Paginator
     */
    public function createPaginator(QueryBuilder $qb, bool $fetchJoinCollection = false, string $prefix = null, int $queryHydrationMode = Query::HYDRATE_OBJECT): Paginator;
```

- Convert paginator to Array Result
```php
    /**
     * @param Paginator $paginator
     * @param bool $isJsonModel
     * @param array $extraParams
     * @return array
     */
    public function getPaginatorArrayResult(Paginator $paginator, bool $isJsonModel = true, array $extraParams = []): array
    
```

- Compare current url with other route and redirect if not is the same
```php
    /**
     * @param string $routeName
     * @param array $routeParams
     * @param int $statusCode
     * @return HttpResponse|null
     */
    public function checkAndRedirectUrl(string $routeName, array $routeParams = [], int $statusCode = 301): ?HttpResponse
    
```

- Get MvcTranslator
```php
    /**
     * @return MvcTranslator
     */
    public function getMvcTranslator(): MvcTranslator
    
```

- Get Translator
```php
    /**
     * @return Translator
     */
    public function getTranslator(): Translator
```

- translate method directly inside controller
```php

    /**
     * @param $message
     * @param string $textDomain
     * @param string|null $locale
     * @return string
     */
    public function translate($message, string $textDomain = 'default', string $locale = null): string
```
    
- Get Router
```php

   /**
    * @return TranslatorAwareTreeRouteStack|TreeRouteStack|SimpleRouteStack|RouteStackInterface
    */
   public function getRouter(): RouteStackInterface
    
```
    
- Get Doctrine ORM EntityManager
```php
    /**
     * @return EntityManager
     */
    public function getEntityManager(): EntityManager
```


### `ZF3Belcebur\MVCBasicTools\FormManagerController` Methods

- Extends `ZF3Belcebur\MVCBasicTools\BaseController`
- getFormManager();
    - return `\Zend\Form\FormElementManager\FormElementManagerV3Polyfill`

## Controller Factories

- `ZF3Belcebur\MVCBasicTools\Factory\ControllerBaseControllerFactory`
- `ZF3Belcebur\MVCBasicTools\Factory\ControllerFormManagerControllerFactory`



## View Helper

```php
<?php 
use ZF3Belcebur\MvcBasicTools\View\Helper\BTools;
/** @var BTools $bTools */
$bTools=$this->bTools();

BTools::nl2br("\n");
BTools::br2nl('<br><br>');
BTools::trim('    ');
BTools::randomPassword(10);
BTools::convertToUTF8('hello');
BTools::splitTextByDotOrSpace('cut this',false,3); // cut...
BTools::slugify('حولا كيو تل'); // 'hwla-kyw-tl'
BTools::slugify('你好，这样的'); // 'ni-hao-zhe-yang-de'
BTools::slugify('그러한 안녕하세요'); // 'geuleohan-annyeonghaseyo'
BTools::slugify('त्यस्तो नमस्'); // 'tyasto-namas'
BTools::slugify('hola que tal'); // 'hola-que-tal'
BTools::slugify('привет, что такой'); // 'privet-cto-takoj'

$bTools->getParams(); // return Params Plugin Controller 
$bTools->getPluginManager(); 
$bTools->getMetaBy('name','description'); // get meta name=description content 
$bTools->getMetaBy('property','og:locale'); // get meta property=og:locale content 
$bTools->getMetaByName('description','things'); // get meta name=description content, if not exist return 'things" 
$bTools->getMetaByProperty('og:locale'); // get meta property=og:locale content
$bTools->getRouteMatch();
$bTools->getRouter();
$bTools->getFormElementManager();
$bTools->getMvcTranslator();
 
// Only in view
$bTools->getParams()->fromRoute(); // return array params from route
//Layout Or View
$bTools->getParamsFromRoute(); // return array params from route
$bTools->getParamFromRoute('param','default'); // return param from route or default value
$bTools->getParams()->fromRoute(); // return array params from route 

```
