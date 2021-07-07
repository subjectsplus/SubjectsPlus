<?php

namespace Symfony\Config\Framework;

require_once __DIR__.\DIRECTORY_SEPARATOR.'HttpClient'.\DIRECTORY_SEPARATOR.'DefaultOptionsConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'HttpClient'.\DIRECTORY_SEPARATOR.'ScopedClientConfig.php';

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class HttpClientConfig 
{
    private $enabled;
    private $maxHostConnections;
    private $defaultOptions;
    private $mockResponseFactory;
    private $scopedClients;
    
    /**
     * @default true
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function enabled($value): self
    {
        $this->enabled = $value;
    
        return $this;
    }
    
    /**
     * The maximum number of connections to a single host.
     * @default null
     * @param ParamConfigurator|int $value
     * @return $this
     */
    public function maxHostConnections($value): self
    {
        $this->maxHostConnections = $value;
    
        return $this;
    }
    
    public function defaultOptions(array $value = []): \Symfony\Config\Framework\HttpClient\DefaultOptionsConfig
    {
        if (null === $this->defaultOptions) {
            $this->defaultOptions = new \Symfony\Config\Framework\HttpClient\DefaultOptionsConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "defaultOptions()" has already been initialized. You cannot pass values the second time you call defaultOptions().');
        }
    
        return $this->defaultOptions;
    }
    
    /**
     * The id of the service that should generate mock responses. It should be either an invokable or an iterable.
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function mockResponseFactory($value): self
    {
        $this->mockResponseFactory = $value;
    
        return $this;
    }
    
    public function scopedClient(string $name, array $value = []): \Symfony\Config\Framework\HttpClient\ScopedClientConfig
    {
        if (!isset($this->scopedClients[$name])) {
            return $this->scopedClients[$name] = new \Symfony\Config\Framework\HttpClient\ScopedClientConfig($value);
        }
        if ([] === $value) {
            return $this->scopedClients[$name];
        }
    
        throw new InvalidConfigurationException('The node created by "scopedClient()" has already been initialized. You cannot pass values the second time you call scopedClient().');
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['enabled'])) {
            $this->enabled = $value['enabled'];
            unset($value['enabled']);
        }
    
        if (isset($value['max_host_connections'])) {
            $this->maxHostConnections = $value['max_host_connections'];
            unset($value['max_host_connections']);
        }
    
        if (isset($value['default_options'])) {
            $this->defaultOptions = new \Symfony\Config\Framework\HttpClient\DefaultOptionsConfig($value['default_options']);
            unset($value['default_options']);
        }
    
        if (isset($value['mock_response_factory'])) {
            $this->mockResponseFactory = $value['mock_response_factory'];
            unset($value['mock_response_factory']);
        }
    
        if (isset($value['scoped_clients'])) {
            $this->scopedClients = array_map(function ($v) { return new \Symfony\Config\Framework\HttpClient\ScopedClientConfig($v); }, $value['scoped_clients']);
            unset($value['scoped_clients']);
        }
    
        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }
    
    
    public function toArray(): array
    {
        $output = [];
        if (null !== $this->enabled) {
            $output['enabled'] = $this->enabled;
        }
        if (null !== $this->maxHostConnections) {
            $output['max_host_connections'] = $this->maxHostConnections;
        }
        if (null !== $this->defaultOptions) {
            $output['default_options'] = $this->defaultOptions->toArray();
        }
        if (null !== $this->mockResponseFactory) {
            $output['mock_response_factory'] = $this->mockResponseFactory;
        }
        if (null !== $this->scopedClients) {
            $output['scoped_clients'] = array_map(function ($v) { return $v->toArray(); }, $this->scopedClients);
        }
    
        return $output;
    }
    

}
