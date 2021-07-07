<?php

namespace Symfony\Config;

require_once __DIR__.\DIRECTORY_SEPARATOR.'TwigExtra'.\DIRECTORY_SEPARATOR.'CacheConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'TwigExtra'.\DIRECTORY_SEPARATOR.'HtmlConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'TwigExtra'.\DIRECTORY_SEPARATOR.'MarkdownConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'TwigExtra'.\DIRECTORY_SEPARATOR.'IntlConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'TwigExtra'.\DIRECTORY_SEPARATOR.'CssinlinerConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'TwigExtra'.\DIRECTORY_SEPARATOR.'InkyConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'TwigExtra'.\DIRECTORY_SEPARATOR.'StringConfig.php';

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class TwigExtraConfig implements \Symfony\Component\Config\Builder\ConfigBuilderInterface
{
    private $cache;
    private $html;
    private $markdown;
    private $intl;
    private $cssinliner;
    private $inky;
    private $string;
    
    public function cache(array $value = []): \Symfony\Config\TwigExtra\CacheConfig
    {
        if (null === $this->cache) {
            $this->cache = new \Symfony\Config\TwigExtra\CacheConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "cache()" has already been initialized. You cannot pass values the second time you call cache().');
        }
    
        return $this->cache;
    }
    
    public function html(array $value = []): \Symfony\Config\TwigExtra\HtmlConfig
    {
        if (null === $this->html) {
            $this->html = new \Symfony\Config\TwigExtra\HtmlConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "html()" has already been initialized. You cannot pass values the second time you call html().');
        }
    
        return $this->html;
    }
    
    public function markdown(array $value = []): \Symfony\Config\TwigExtra\MarkdownConfig
    {
        if (null === $this->markdown) {
            $this->markdown = new \Symfony\Config\TwigExtra\MarkdownConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "markdown()" has already been initialized. You cannot pass values the second time you call markdown().');
        }
    
        return $this->markdown;
    }
    
    public function intl(array $value = []): \Symfony\Config\TwigExtra\IntlConfig
    {
        if (null === $this->intl) {
            $this->intl = new \Symfony\Config\TwigExtra\IntlConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "intl()" has already been initialized. You cannot pass values the second time you call intl().');
        }
    
        return $this->intl;
    }
    
    public function cssinliner(array $value = []): \Symfony\Config\TwigExtra\CssinlinerConfig
    {
        if (null === $this->cssinliner) {
            $this->cssinliner = new \Symfony\Config\TwigExtra\CssinlinerConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "cssinliner()" has already been initialized. You cannot pass values the second time you call cssinliner().');
        }
    
        return $this->cssinliner;
    }
    
    public function inky(array $value = []): \Symfony\Config\TwigExtra\InkyConfig
    {
        if (null === $this->inky) {
            $this->inky = new \Symfony\Config\TwigExtra\InkyConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "inky()" has already been initialized. You cannot pass values the second time you call inky().');
        }
    
        return $this->inky;
    }
    
    public function string(array $value = []): \Symfony\Config\TwigExtra\StringConfig
    {
        if (null === $this->string) {
            $this->string = new \Symfony\Config\TwigExtra\StringConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "string()" has already been initialized. You cannot pass values the second time you call string().');
        }
    
        return $this->string;
    }
    
    public function getExtensionAlias(): string
    {
        return 'twig_extra';
    }
            
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['cache'])) {
            $this->cache = new \Symfony\Config\TwigExtra\CacheConfig($value['cache']);
            unset($value['cache']);
        }
    
        if (isset($value['html'])) {
            $this->html = new \Symfony\Config\TwigExtra\HtmlConfig($value['html']);
            unset($value['html']);
        }
    
        if (isset($value['markdown'])) {
            $this->markdown = new \Symfony\Config\TwigExtra\MarkdownConfig($value['markdown']);
            unset($value['markdown']);
        }
    
        if (isset($value['intl'])) {
            $this->intl = new \Symfony\Config\TwigExtra\IntlConfig($value['intl']);
            unset($value['intl']);
        }
    
        if (isset($value['cssinliner'])) {
            $this->cssinliner = new \Symfony\Config\TwigExtra\CssinlinerConfig($value['cssinliner']);
            unset($value['cssinliner']);
        }
    
        if (isset($value['inky'])) {
            $this->inky = new \Symfony\Config\TwigExtra\InkyConfig($value['inky']);
            unset($value['inky']);
        }
    
        if (isset($value['string'])) {
            $this->string = new \Symfony\Config\TwigExtra\StringConfig($value['string']);
            unset($value['string']);
        }
    
        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }
    
    
    public function toArray(): array
    {
        $output = [];
        if (null !== $this->cache) {
            $output['cache'] = $this->cache->toArray();
        }
        if (null !== $this->html) {
            $output['html'] = $this->html->toArray();
        }
        if (null !== $this->markdown) {
            $output['markdown'] = $this->markdown->toArray();
        }
        if (null !== $this->intl) {
            $output['intl'] = $this->intl->toArray();
        }
        if (null !== $this->cssinliner) {
            $output['cssinliner'] = $this->cssinliner->toArray();
        }
        if (null !== $this->inky) {
            $output['inky'] = $this->inky->toArray();
        }
        if (null !== $this->string) {
            $output['string'] = $this->string->toArray();
        }
    
        return $output;
    }
    

}
