<?php

namespace Symfony\Config\Framework;

require_once __DIR__.\DIRECTORY_SEPARATOR.'RateLimiter'.\DIRECTORY_SEPARATOR.'LimiterConfig.php';

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class RateLimiterConfig 
{
    private $enabled;
    private $limiters;
    
    /**
     * @default false
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function enabled($value): self
    {
        $this->enabled = $value;
    
        return $this;
    }
    
    public function limiter(string $name, array $value = []): \Symfony\Config\Framework\RateLimiter\LimiterConfig
    {
        if (!isset($this->limiters[$name])) {
            return $this->limiters[$name] = new \Symfony\Config\Framework\RateLimiter\LimiterConfig($value);
        }
        if ([] === $value) {
            return $this->limiters[$name];
        }
    
        throw new InvalidConfigurationException('The node created by "limiter()" has already been initialized. You cannot pass values the second time you call limiter().');
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['enabled'])) {
            $this->enabled = $value['enabled'];
            unset($value['enabled']);
        }
    
        if (isset($value['limiters'])) {
            $this->limiters = array_map(function ($v) { return new \Symfony\Config\Framework\RateLimiter\LimiterConfig($v); }, $value['limiters']);
            unset($value['limiters']);
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
        if (null !== $this->limiters) {
            $output['limiters'] = array_map(function ($v) { return $v->toArray(); }, $this->limiters);
        }
    
        return $output;
    }
    

}
