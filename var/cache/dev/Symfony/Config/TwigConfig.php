<?php

namespace Symfony\Config;

require_once __DIR__.\DIRECTORY_SEPARATOR.'Twig'.\DIRECTORY_SEPARATOR.'GlobalConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'Twig'.\DIRECTORY_SEPARATOR.'DateConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'Twig'.\DIRECTORY_SEPARATOR.'NumberFormatConfig.php';

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class TwigConfig implements \Symfony\Component\Config\Builder\ConfigBuilderInterface
{
    private $formThemes;
    private $globals;
    private $autoescape;
    private $autoescapeService;
    private $autoescapeServiceMethod;
    private $baseTemplateClass;
    private $cache;
    private $charset;
    private $debug;
    private $strictVariables;
    private $autoReload;
    private $optimizations;
    private $defaultPath;
    private $paths;
    private $date;
    private $numberFormat;
    
    /**
     * @param ParamConfigurator|list<mixed|ParamConfigurator> $value
     * @return $this
     */
    public function formThemes($value): self
    {
        $this->formThemes = $value;
    
        return $this;
    }
    
    public function global(string $key, array $value = []): \Symfony\Config\Twig\GlobalConfig
    {
        if (!isset($this->globals[$key])) {
            return $this->globals[$key] = new \Symfony\Config\Twig\GlobalConfig($value);
        }
        if ([] === $value) {
            return $this->globals[$key];
        }
    
        throw new InvalidConfigurationException('The node created by "global()" has already been initialized. You cannot pass values the second time you call global().');
    }
    
    /**
     * @default 'name'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function autoescape($value = 'name'): self
    {
        $this->autoescape = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function autoescapeService($value): self
    {
        $this->autoescapeService = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function autoescapeServiceMethod($value): self
    {
        $this->autoescapeServiceMethod = $value;
    
        return $this;
    }
    
    /**
     * @example Twig\Template
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function baseTemplateClass($value): self
    {
        $this->baseTemplateClass = $value;
    
        return $this;
    }
    
    /**
     * @default '%kernel.cache_dir%/twig'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function cache($value): self
    {
        $this->cache = $value;
    
        return $this;
    }
    
    /**
     * @default '%kernel.charset%'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function charset($value): self
    {
        $this->charset = $value;
    
        return $this;
    }
    
    /**
     * @default '%kernel.debug%'
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function debug($value): self
    {
        $this->debug = $value;
    
        return $this;
    }
    
    /**
     * @default '%kernel.debug%'
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function strictVariables($value): self
    {
        $this->strictVariables = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function autoReload($value): self
    {
        $this->autoReload = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|int $value
     * @return $this
     */
    public function optimizations($value): self
    {
        $this->optimizations = $value;
    
        return $this;
    }
    
    /**
     * The default path used to load templates
     * @default '%kernel.project_dir%/templates'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function defaultPath($value): self
    {
        $this->defaultPath = $value;
    
        return $this;
    }
    
    /**
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function path(string $paths, $value): self
    {
        $this->paths[$paths] = $value;
    
        return $this;
    }
    
    public function date(array $value = []): \Symfony\Config\Twig\DateConfig
    {
        if (null === $this->date) {
            $this->date = new \Symfony\Config\Twig\DateConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "date()" has already been initialized. You cannot pass values the second time you call date().');
        }
    
        return $this->date;
    }
    
    public function numberFormat(array $value = []): \Symfony\Config\Twig\NumberFormatConfig
    {
        if (null === $this->numberFormat) {
            $this->numberFormat = new \Symfony\Config\Twig\NumberFormatConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "numberFormat()" has already been initialized. You cannot pass values the second time you call numberFormat().');
        }
    
        return $this->numberFormat;
    }
    
    public function getExtensionAlias(): string
    {
        return 'twig';
    }
            
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['form_themes'])) {
            $this->formThemes = $value['form_themes'];
            unset($value['form_themes']);
        }
    
        if (isset($value['globals'])) {
            $this->globals = array_map(function ($v) { return new \Symfony\Config\Twig\GlobalConfig($v); }, $value['globals']);
            unset($value['globals']);
        }
    
        if (isset($value['autoescape'])) {
            $this->autoescape = $value['autoescape'];
            unset($value['autoescape']);
        }
    
        if (isset($value['autoescape_service'])) {
            $this->autoescapeService = $value['autoescape_service'];
            unset($value['autoescape_service']);
        }
    
        if (isset($value['autoescape_service_method'])) {
            $this->autoescapeServiceMethod = $value['autoescape_service_method'];
            unset($value['autoescape_service_method']);
        }
    
        if (isset($value['base_template_class'])) {
            $this->baseTemplateClass = $value['base_template_class'];
            unset($value['base_template_class']);
        }
    
        if (isset($value['cache'])) {
            $this->cache = $value['cache'];
            unset($value['cache']);
        }
    
        if (isset($value['charset'])) {
            $this->charset = $value['charset'];
            unset($value['charset']);
        }
    
        if (isset($value['debug'])) {
            $this->debug = $value['debug'];
            unset($value['debug']);
        }
    
        if (isset($value['strict_variables'])) {
            $this->strictVariables = $value['strict_variables'];
            unset($value['strict_variables']);
        }
    
        if (isset($value['auto_reload'])) {
            $this->autoReload = $value['auto_reload'];
            unset($value['auto_reload']);
        }
    
        if (isset($value['optimizations'])) {
            $this->optimizations = $value['optimizations'];
            unset($value['optimizations']);
        }
    
        if (isset($value['default_path'])) {
            $this->defaultPath = $value['default_path'];
            unset($value['default_path']);
        }
    
        if (isset($value['paths'])) {
            $this->paths = $value['paths'];
            unset($value['paths']);
        }
    
        if (isset($value['date'])) {
            $this->date = new \Symfony\Config\Twig\DateConfig($value['date']);
            unset($value['date']);
        }
    
        if (isset($value['number_format'])) {
            $this->numberFormat = new \Symfony\Config\Twig\NumberFormatConfig($value['number_format']);
            unset($value['number_format']);
        }
    
        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }
    
    
    public function toArray(): array
    {
        $output = [];
        if (null !== $this->formThemes) {
            $output['form_themes'] = $this->formThemes;
        }
        if (null !== $this->globals) {
            $output['globals'] = array_map(function ($v) { return $v->toArray(); }, $this->globals);
        }
        if (null !== $this->autoescape) {
            $output['autoescape'] = $this->autoescape;
        }
        if (null !== $this->autoescapeService) {
            $output['autoescape_service'] = $this->autoescapeService;
        }
        if (null !== $this->autoescapeServiceMethod) {
            $output['autoescape_service_method'] = $this->autoescapeServiceMethod;
        }
        if (null !== $this->baseTemplateClass) {
            $output['base_template_class'] = $this->baseTemplateClass;
        }
        if (null !== $this->cache) {
            $output['cache'] = $this->cache;
        }
        if (null !== $this->charset) {
            $output['charset'] = $this->charset;
        }
        if (null !== $this->debug) {
            $output['debug'] = $this->debug;
        }
        if (null !== $this->strictVariables) {
            $output['strict_variables'] = $this->strictVariables;
        }
        if (null !== $this->autoReload) {
            $output['auto_reload'] = $this->autoReload;
        }
        if (null !== $this->optimizations) {
            $output['optimizations'] = $this->optimizations;
        }
        if (null !== $this->defaultPath) {
            $output['default_path'] = $this->defaultPath;
        }
        if (null !== $this->paths) {
            $output['paths'] = $this->paths;
        }
        if (null !== $this->date) {
            $output['date'] = $this->date->toArray();
        }
        if (null !== $this->numberFormat) {
            $output['number_format'] = $this->numberFormat->toArray();
        }
    
        return $output;
    }
    

}
