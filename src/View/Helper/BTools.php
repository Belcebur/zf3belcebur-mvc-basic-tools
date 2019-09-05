<?php
/**
 * Created by PhpStorm.
 * User: Deibi
 * Date: 28/05/2018
 * Time: 20:00
 */

namespace ZF3Belcebur\MvcBasicTools\View\Helper;


use Zend\Form\FormElementManager\FormElementManagerV3Polyfill;
use Zend\Http\PhpEnvironment\Request;
use Zend\I18n\Translator\TranslatorInterface;
use Zend\Mvc\Controller\Plugin\Params as ParamsPlugin;
use Zend\Mvc\Controller\PluginManager;
use Zend\Mvc\I18n\Router\TranslatorAwareTreeRouteStack;
use Zend\Mvc\I18n\Translator as MvcTranslator;
use Zend\Mvc\InjectApplicationEventInterface;
use Zend\Router\Http\RouteMatch;
use Zend\Router\Http\TreeRouteStack;
use Zend\Router\RouteStackInterface;
use Zend\Router\SimpleRouteStack;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Renderer\PhpRenderer;
use function array_key_exists;
use function is_string;
use function preg_match;
use function strip_tags;
use function strlen;
use function strrpos;
use function substr;


/**
 * Class Tools
 * @package ZF3Belcebur\MvcBasicTools\View\Helper
 * @method PhpRenderer getView()
 */
class BTools extends AbstractHelper
{
    /**
     * @var TranslatorAwareTreeRouteStack|TreeRouteStack|SimpleRouteStack|RouteStackInterface
     */
    protected $router;

    /**
     * @var FormElementManagerV3Polyfill
     */
    protected $formElementManager;

    /**
     * @var RouteMatch
     */
    protected $routeMatch;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var ParamsPlugin
     */
    protected $params;

    /**
     * @var PluginManager
     */
    protected $pluginManager;
    /**
     * @var TranslatorInterface|MvcTranslator
     */
    private $mvcTranslator;

    /**
     * Tools constructor.
     * @param MvcTranslator|TranslatorInterface $mvcTranslator
     * @param TranslatorAwareTreeRouteStack|TreeRouteStack|SimpleRouteStack|RouteStackInterface $router
     * @param Request $request
     * @param FormElementManagerV3Polyfill $formElementManager
     * @param PluginManager $pluginManager
     */
    public function __construct(TranslatorInterface $mvcTranslator, RouteStackInterface $router, Request $request, FormElementManagerV3Polyfill $formElementManager, PluginManager $pluginManager)
    {
        $this->router = $router;
        $this->request = $request;
        $this->formElementManager = $formElementManager;
        $this->pluginManager = $pluginManager;
        $this->params = $pluginManager->get(ParamsPlugin::class);
        $this->mvcTranslator = $mvcTranslator;
    }

    /**
     * @param string $string
     * @param int $joinBrs
     * @return string
     */
    public static function nl2br(string $string, int $joinBrs = 2): string
    {
        $exploded = preg_split('/\s{2,}/', preg_replace('/( ){2,}/', ' ', self::br2nl($string)));
        if (count($exploded) === 1) {
            return self::trim(current($exploded));
        }

        return implode(implode('', array_fill(0, $joinBrs, '<br/>')), $exploded);
    }

    /**
     * Convert BR tags to newlines and carriage returns.
     *
     * @param string $string The string to convert
     * @param string $separator The string to use as line separator
     * @param string $trimPattern trim result
     *
     * @return string The converted string
     */
    public static function br2nl(string $string, string $separator = PHP_EOL, string $trimPattern = ''): string
    {
        $separator = in_array($separator, ["\n", "\r", "\r\n", "\n\r", chr(30), chr(155), PHP_EOL], false) ? $separator : PHP_EOL;

        $content = preg_replace('/<br(\s*)?\/?>/i', $separator, $string);

        if (preg_match($trimPattern, '') !== false) {
            return self::trim($content, $trimPattern);
        }

        return $content;
    }

    /**
     * @param string $val
     * @param string $pattern
     * @return string
     */
    public static function trim(string $val, string $pattern = '#^(\d\.\s|&nbsp;)#'): string
    {
        return trim(preg_replace([$pattern], [''], trim(html_entity_decode($val), " \t\n\r\0\x0B\xC2\xA0")), " \t\n\r\0\x0B\xC2\xA0");
    }

    /**
     * @param int $length
     * @param string $chars
     * @return string
     */
    public static function randomPassword(int $length = 8, string $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!$%^&_-.?'): string
    {
        return substr(str_shuffle($chars), 0, $length);
    }

    /**
     * @param string $text
     * @param bool $stripTags
     * @param int $maxLength
     * @return string
     */
    public static function splitTextByDotOrSpace(string $text, bool $stripTags = false, int $maxLength = 250): string
    {

        if ($stripTags) {
            $text = strip_tags($text);
        }
        $part = substr($text, 0, $maxLength);
        $dotPosition = (int)strrpos($part, '.');
        $spacePosition = (int)strrpos($part, ' ');
        $return = $part;


        if ($dotPosition !== false && $dotPosition >= ($maxLength * 0.8)) {
            $return = substr($part, 0, $dotPosition + 1);
        } elseif ($spacePosition !== false) {
            $return = substr($part, 0, $spacePosition) . ' ...';
        }

        return $return;
    }

    /**
     *
     * @param string $text
     * @param array $replace
     * @param string $delimiter
     *
     * @return string
     */
    public static function slugify(string $text, array $replace = [], string $delimiter = '-'): string
    {

        $str = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', transliterator_transliterate('Any-Latin; Latin-ASCII', self::convertToUTF8($text)));

        if (!empty($replace)) {
            $str = str_replace($replace, ' ', $str);
        }

        $initialChars = [
            'Á' => 'A', 'Ç' => 'c', 'É' => 'e', 'Í' => 'i', 'Ñ' => 'n', 'Ó' => 'o', 'Ú' => 'u', 'á' => 'a', 'ç' => 'c', 'é' => 'e', 'í' => 'i',
            'ñ' => 'n', 'ó' => 'o', 'ú' => 'u', 'à' => 'a', 'è' => 'e', 'ì' => 'i', 'ò' => 'o', 'ù' => 'u', 'ä' => 'a', 'Ä' => 'A', 'ā' => 'a',
            'Ḩ' => 'H', 'Š' => 'S', 'š' => 's', 'Đ' => 'Dj', 'đ' => 'dj', 'Ž' => 'Z', 'ž' => 'z', 'Č' => 'C', 'č' => 'c', 'Ć' => 'C', 'ć' => 'c',
            'À' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Å' => 'A', 'Æ' => 'A', 'È' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Î' => 'I', 'Ï' => 'I',
            'Ò' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U', 'Ü' => 'U', 'Û' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss',
            'â' => 'a', 'ã' => 'a', 'å' => 'a', 'æ' => 'a', 'ê' => 'e', 'ë' => 'e', 'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ô' => 'o', 'õ' => 'o',
            'ö' => 'o', 'ø' => 'o', 'ü' => 'u', 'û' => 'u', 'ý' => 'y', 'þ' => 'b', 'ÿ' => 'y', 'Ŕ' => 'R', 'ŕ' => 'r', 'ū' => 'u', 'ī' => 'i',
            'Ā' => 'A', 'Ş' => 'S', 'ḩ' => 'h',];

        $cyrillicFrom = [
            'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ',
            'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у',
            'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я',];

        $cyrillicTo = [
            'A', 'B', 'W', 'G', 'D', 'Ie', 'Io', 'Z', 'Z', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'Ch', 'C', 'Tch', 'Sh',
            'Shtch', '', 'Y', '', 'E', 'Iu', 'Ia', 'a', 'b', 'w', 'g', 'd', 'ie', 'io', 'z', 'z', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's',
            't', 'u', 'f', 'ch', 'c', 'tch', 'sh', 'shtch', '', 'y', '', 'e', 'iu', 'ia',];

        $otherFrom = [
            'Á', 'À', 'Â', 'Ä', 'Ă', 'Ā', 'Ã', 'Å', 'Ą', 'Æ', 'Ć', 'Ċ', 'Ĉ', 'Č', 'Ç', 'Ď', 'Đ', 'Ð', 'É', 'È', 'Ė', 'Ê', 'Ë', 'Ě', 'Ē', 'Ę', 'Ə',
            'Ġ', 'Ĝ', 'Ğ', 'Ģ', 'á', 'à', 'â', 'ä', 'ă', 'ā', 'ã', 'å', 'ą', 'æ', 'ć', 'ċ', 'ĉ', 'č', 'ç', 'ď', 'đ', 'ð', 'é', 'è', 'ė', 'ê', 'ë',
            'ě', 'ē', 'ę', 'ə', 'ġ', 'ĝ', 'ğ', 'ģ', 'Ĥ', 'Ħ', 'I', 'Í', 'Ì', 'İ', 'Î', 'Ï', 'Ī', 'Į', 'Ĳ', 'Ĵ', 'Ķ', 'Ļ', 'Ł', 'Ń', 'Ň', 'Ñ', 'Ņ',
            'Ó', 'Ò', 'Ô', 'Ö', 'Õ', 'Ő', 'Ø', 'Ơ', 'Œ', 'ĥ', 'ħ', 'ı', 'í', 'ì', 'i', 'î', 'ï', 'ī', 'į', 'ĳ', 'ĵ', 'ķ', 'ļ', 'ł', 'ń', 'ň', 'ñ',
            'ņ', 'ó', 'ò', 'ô', 'ö', 'õ', 'ő', 'ø', 'ơ', 'œ', 'Ŕ', 'Ř', 'Ś', 'Ŝ', 'Š', 'Ş', 'Ť', 'Ţ', 'Þ', 'Ú', 'Ù', 'Û', 'Ü', 'Ŭ', 'Ū', 'Ů', 'Ų',
            'Ű', 'Ư', 'Ŵ', 'Ý', 'Ŷ', 'Ÿ', 'Ź', 'Ż', 'Ž', 'ŕ', 'ř', 'ś', 'ŝ', 'š', 'ş', 'ß', 'ť', 'ţ', 'þ', 'ú', 'ù', 'û', 'ü', 'ŭ', 'ū', 'ů', 'ų',
            'ű', 'ư', 'ŵ', 'ý', 'ŷ', 'ÿ', 'ź', 'ż', 'ž',];

        $otherTo = [
            'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'C', 'C', 'C', 'C', 'D', 'D', 'D', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'G',
            'G', 'G', 'G', 'G', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'c', 'c', 'c', 'c', 'd', 'd', 'd', 'e', 'e', 'e', 'e', 'e',
            'e', 'e', 'e', 'g', 'g', 'g', 'g', 'g', 'H', 'H', 'I', 'I', 'I', 'I', 'I', 'I', 'I', 'I', 'IJ', 'J', 'K', 'L', 'L', 'N', 'N', 'N', 'N',
            'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'CE', 'h', 'h', 'i', 'i', 'i', 'i', 'i', 'i', 'i', 'i', 'ij', 'j', 'k', 'l', 'l', 'n', 'n', 'n',
            'n', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'R', 'R', 'S', 'S', 'S', 'S', 'T', 'T', 'T', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U',
            'U', 'U', 'W', 'Y', 'Y', 'Y', 'Z', 'Z', 'Z', 'r', 'r', 's', 's', 's', 's', 'B', 't', 't', 'b', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u',
            'u', 'u', 'w', 'y', 'y', 'y', 'z', 'z', 'z',];

        $otherChars = array_combine($otherFrom, $otherTo);
        $cyrillicChars = array_combine($cyrillicFrom, $cyrillicTo);
        $characters = array_merge($initialChars, $cyrillicChars, $otherChars);

        $str = strtr($str, $characters);
        $str = strtolower(trim($str));
        $str = preg_replace('/[^a-z0-9-]/', '-', $str);
        $str = preg_replace('/-+/', '-', $str);

        if (substr($str, strlen($str) - 1, strlen($str)) === '-') {
            $str = substr($str, 0, -1);
        }

        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $clean = preg_replace('/[^a-zA-Z0-9\/_|+ -]/', '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace('/[\/_|+ -]+/', $delimiter, $clean);

        if ($clean === '') {
            return $str;
        }

        return $clean;
    }

    /**
     * @param string $text
     * @return string
     */
    public static function convertToUTF8(string $text): string
    {
        if (is_string($text)) {

            $encoding = mb_detect_encoding($text, mb_detect_order(), false);
            if ($encoding === 'UTF-8') {
                $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
            }

            $out = iconv(mb_detect_encoding($text, mb_detect_order(), false), 'UTF-8//IGNORE', $text);

            return $out;
        }
        return $text;
    }

    /**
     * @return PluginManager
     */
    public function getPluginManager(): PluginManager
    {
        return $this->pluginManager;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @return ParamsPlugin
     * @uses  if you want to get params from route use the getParamsFromRoute or getParamFromRoute methods
     */
    public function getParams(): ParamsPlugin
    {
        return $this->params;
    }

    /**
     * @param string $typeValue description,theme-color, ...
     * @param string $defaultValue default value if not exist
     * @return string|null
     */
    public function getMetaByProperty(string $typeValue, ?string $defaultValue = null): ?string
    {
        return $this->getMetaBy('property', $typeValue, $defaultValue);
    }

    /**
     * @param string $type property,name, ...
     * @param string $typeValue title, og:title, og:locale, ...
     * @param string $defaultValue default value if not exist
     * @return string|null
     */
    public function getMetaBy(string $type, string $typeValue, ?string $defaultValue = null): ?string
    {
        $view = $this->getView();
        $headMeta = $view->headMeta();
        foreach ($headMeta->getContainer() as $meta) {
            $metaArray = (array)$meta;
            if (array_key_exists($type, $metaArray) && $metaArray[$type] === $typeValue) {
                return $metaArray['content'] ?? null;
            }
        }
        return $defaultValue;
    }

    /**
     * @param string $typeValue description,theme-color, ...
     * @param string $defaultValue default value if not exist
     * @return string|null
     */
    public function getMetaByName(string $typeValue, ?string $defaultValue = null): ?string
    {
        return $this->getMetaBy('name', $typeValue, $defaultValue);
    }

    /**
     * @return RouteMatch
     */
    public function getRouteMatch(): ?RouteMatch
    {
        return $this->router->match($this->request);
    }

    /**
     * @return TranslatorAwareTreeRouteStack|TreeRouteStack|SimpleRouteStack|RouteStackInterface
     */
    public function getRouter(): RouteStackInterface
    {
        return $this->router;
    }

    /**
     * @return FormElementManagerV3Polyfill
     */
    public function getFormElementManager(): FormElementManagerV3Polyfill
    {
        return $this->formElementManager;
    }

    /**
     * @return TranslatorInterface|MvcTranslator
     */
    public function getMvcTranslator(): TranslatorInterface
    {
        return $this->mvcTranslator;
    }

    /**
     * @param string|null $param
     * @param null $default
     * @return mixed|null
     */
    public function getParamFromRoute(string $param = null, $default = null)
    {
        return $this->getParamsFromRoute()[$param] ?? $default;

    }

    /**
     * @return array
     */
    public function getParamsFromRoute(): array
    {
        $params = $this->getParams();
        if ($params->getController() instanceof InjectApplicationEventInterface) {
            return $params->fromRoute();
        }
        $routeMatch = $this->getRouteMatch();
        if ($routeMatch) {
            return $routeMatch->getParams();
        }
        return [];
    }

}
