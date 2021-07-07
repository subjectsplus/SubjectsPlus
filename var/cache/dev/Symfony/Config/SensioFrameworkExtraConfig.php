<?php

namespace Symfony\Config;

require_once __DIR__.\DIRECTORY_SEPARATOR.'SensioFrameworkExtra'.\DIRECTORY_SEPARATOR.'RouterConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'SensioFrameworkExtra'.\DIRECTORY_SEPARATOR.'RequestConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'SensioFrameworkExtra'.\DIRECTORY_SEPARATOR.'ViewConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'SensioFrameworkExtra'.\DIRECTORY_SEPARATOR.'CacheConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'SensioFrameworkExtra'.\DIRECTORY_SEPARATOR.'SecurityConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'SensioFrameworkExtra'.\DIRECTORY_SEPARATOR.'TemplatingConfig.php';

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class SensioFrameworkExtraConfig implements \Symfony\Component\Config\Builder\ConfigBuilderInterface
{
    private $router;
    private $request;
    private $view;
    private $cache;
    private $security;
    private $templating;
    
    public function router(array $value = []): \Symfony\Config\SensioFrameworkExtra\RouterConfig
    {
        if (null === $this->router) {
            $this->router = new \Symfony\Config\SensioFrameworkExtra\RouterConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "router()" has already been initialized. You cannot pass values the second time you call router().');
        }
    
        return $this->router;
    }
    
    public function request(array $value = []): \Symfony\Config\SensioFrameworkExtra\RequestConfig
    {
        if (null === $this->request) {
            $this->request = new \Symfony\Config\SensioFrameworkExtra\RequestConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "request()" has already been initialized. You cannot pass values the second time you call request().');
        }
    
        return $this->request;
    }
    
    public function view(array $value = []): \Symfony\Config\SensioFrameworkExtra\ViewConfig
    {
        if (null === $this->view) {
            $this->view = new \Symfony\Config\SensioFrameworkExtra\ViewConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "view()" has already been initialized. You cannot pass values the second time you call view().');
        }
    
        return $this->view;
    }
    
    public function cache(array $value = []): \Symfony\Config\SensioFrameworkExtra\CacheConfig
    {
        if (null === $this->cache) {
            $this->cache = new \Symfony\Config\SensioFrameworkExtra\CacheConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "cache()" has already been initialized. You cannot pass values the second time you call cache().');
        }
    
        return $this->cache;
    }
    
    public function security(array $value = []): \Symfony\Config\SensioFrameworkExtra\SecurityConfig
    {
        if (null === $this->security) {
            $this->security = new \Symfony\Config\SensioFrameworkExtra\SecurityConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "security()" has already been initialized. You cannot pass values the second time you call security().');
        }
    
        return $this->security;
    }
    
    public function templating(array $value = []): \Symfony\Config\SensioFrameworkExtra\TemplatingConfig
    {
        if (null === $this->templating) {
            $this->templating = new \Symfony\Config\SensioFrameworkExtra\TemplatingConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "templating()" has already been initialized. You cannot pass values the second time you call templating().');
        }
    
        return $this->templating;
    }
    
    public function getExtensionAlias(): string
    {
        return 'sensio_framework_extra';
    }
            
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['router'])) {
            $this->router = new \Symfony\Config\SensioFrameworkExtra\RouterConfig($value['router']);
            unset($value['router']);
        }
    
        if (isset($value['request'])) {
            $this->request = new \Symfony\Config\SensioFrameworkExtra\RequestConfig($value['request']);
            unset($value['request']);
        }
    
        if (isset($value['view'])) {
            $this->view = new \Symfony\Config\SensioFrameworkExtra\ViewConfig($value['view']);
            unset($value['view']);
        }
    
        if (isset($value['cache'])) {
            $this->cache = new \Symfony\Config\SensioFrameworkExtra\CacheConfig($value['cache']);
            unset($value['cache']);
        }
    
        if (isset($value['security'])) {
            $this->security = new \Symfony\Config\SensioFrameworkExtra\SecurityConfig($value['security']);
            unset($value['security']);
        }
    
        if (isset($value['templating'])) {
            $this->templating = new \Symfony\Config\SensioFrameworkExtra\TemplatingConfig($value['templating']);
            unset($value['templating']);
        }
    
        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }
    
    
    public function toArray(): array
    {
        $output = [];
        if (null !== $this->router) {
            $output['router'] = $this->router->toArray();
        }
        if (null !== $this->request) {
            $output['request'] = $this->request->toArray();
        }
        if (null !== $this->view) {
            $output['view'] = $this->view->toArray();
        }
        if (null !== $this->cache) {
            $output['cache'] = $this->cache->toArray();
        }
        if (null !== $this->security) {
            $output['security'] = $this->security->toArray();
        }
        if (null !== $this->templating) {
            $output['templating'] = $this->templating->toArray();
        }
    
        return $output;
    }
    

}
