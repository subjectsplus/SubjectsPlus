<?php

namespace Symfony\Config;

require_once __DIR__.\DIRECTORY_SEPARATOR.'Monolog'.\DIRECTORY_SEPARATOR.'HandlerConfig.php';

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class MonologConfig implements \Symfony\Component\Config\Builder\ConfigBuilderInterface
{
    private $useMicroseconds;
    private $channels;
    private $handlers;
    
    /**
     * @default true
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function useMicroseconds($value): self
    {
        $this->useMicroseconds = $value;
    
        return $this;
    }
    
    /**
     * @param ParamConfigurator|list<mixed|ParamConfigurator> $value
     * @return $this
     */
    public function channels($value): self
    {
        $this->channels = $value;
    
        return $this;
    }
    
    public function handler(string $name, array $value = []): \Symfony\Config\Monolog\HandlerConfig
    {
        if (!isset($this->handlers[$name])) {
            return $this->handlers[$name] = new \Symfony\Config\Monolog\HandlerConfig($value);
        }
        if ([] === $value) {
            return $this->handlers[$name];
        }
    
        throw new InvalidConfigurationException('The node created by "handler()" has already been initialized. You cannot pass values the second time you call handler().');
    }
    
    public function getExtensionAlias(): string
    {
        return 'monolog';
    }
            
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['use_microseconds'])) {
            $this->useMicroseconds = $value['use_microseconds'];
            unset($value['use_microseconds']);
        }
    
        if (isset($value['channels'])) {
            $this->channels = $value['channels'];
            unset($value['channels']);
        }
    
        if (isset($value['handlers'])) {
            $this->handlers = array_map(function ($v) { return new \Symfony\Config\Monolog\HandlerConfig($v); }, $value['handlers']);
            unset($value['handlers']);
        }
    
        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }
    
    
    public function toArray(): array
    {
        $output = [];
        if (null !== $this->useMicroseconds) {
            $output['use_microseconds'] = $this->useMicroseconds;
        }
        if (null !== $this->channels) {
            $output['channels'] = $this->channels;
        }
        if (null !== $this->handlers) {
            $output['handlers'] = array_map(function ($v) { return $v->toArray(); }, $this->handlers);
        }
    
        return $output;
    }
    

}
