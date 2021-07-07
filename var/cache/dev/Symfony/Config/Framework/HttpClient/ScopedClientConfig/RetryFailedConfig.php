<?php

namespace Symfony\Config\Framework\HttpClient\ScopedClientConfig;

require_once __DIR__.\DIRECTORY_SEPARATOR.'RetryFailed'.\DIRECTORY_SEPARATOR.'HttpCodeConfig.php';

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class RetryFailedConfig 
{
    private $enabled;
    private $retryStrategy;
    private $httpCodes;
    private $maxRetries;
    private $delay;
    private $multiplier;
    private $maxDelay;
    private $jitter;
    
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
    
    /**
     * service id to override the retry strategy
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function retryStrategy($value): self
    {
        $this->retryStrategy = $value;
    
        return $this;
    }
    
    public function httpCode(string $code, array $value = []): \Symfony\Config\Framework\HttpClient\ScopedClientConfig\RetryFailed\HttpCodeConfig
    {
        if (!isset($this->httpCodes[$code])) {
            return $this->httpCodes[$code] = new \Symfony\Config\Framework\HttpClient\ScopedClientConfig\RetryFailed\HttpCodeConfig($value);
        }
        if ([] === $value) {
            return $this->httpCodes[$code];
        }
    
        throw new InvalidConfigurationException('The node created by "httpCode()" has already been initialized. You cannot pass values the second time you call httpCode().');
    }
    
    /**
     * @default 3
     * @param ParamConfigurator|int $value
     * @return $this
     */
    public function maxRetries($value): self
    {
        $this->maxRetries = $value;
    
        return $this;
    }
    
    /**
     * Time in ms to delay (or the initial value when multiplier is used)
     * @default 1000
     * @param ParamConfigurator|int $value
     * @return $this
     */
    public function delay($value): self
    {
        $this->delay = $value;
    
        return $this;
    }
    
    /**
     * If greater than 1, delay will grow exponentially for each retry: delay * (multiple ^ retries)
     * @default 2
     * @param ParamConfigurator|float $value
     * @return $this
     */
    public function multiplier($value): self
    {
        $this->multiplier = $value;
    
        return $this;
    }
    
    /**
     * Max time in ms that a retry should ever be delayed (0 = infinite)
     * @default 0
     * @param ParamConfigurator|int $value
     * @return $this
     */
    public function maxDelay($value): self
    {
        $this->maxDelay = $value;
    
        return $this;
    }
    
    /**
     * Randomness in percent (between 0 and 1) to apply to the delay
     * @default 0.1
     * @param ParamConfigurator|float $value
     * @return $this
     */
    public function jitter($value): self
    {
        $this->jitter = $value;
    
        return $this;
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['enabled'])) {
            $this->enabled = $value['enabled'];
            unset($value['enabled']);
        }
    
        if (isset($value['retry_strategy'])) {
            $this->retryStrategy = $value['retry_strategy'];
            unset($value['retry_strategy']);
        }
    
        if (isset($value['http_codes'])) {
            $this->httpCodes = array_map(function ($v) { return new \Symfony\Config\Framework\HttpClient\ScopedClientConfig\RetryFailed\HttpCodeConfig($v); }, $value['http_codes']);
            unset($value['http_codes']);
        }
    
        if (isset($value['max_retries'])) {
            $this->maxRetries = $value['max_retries'];
            unset($value['max_retries']);
        }
    
        if (isset($value['delay'])) {
            $this->delay = $value['delay'];
            unset($value['delay']);
        }
    
        if (isset($value['multiplier'])) {
            $this->multiplier = $value['multiplier'];
            unset($value['multiplier']);
        }
    
        if (isset($value['max_delay'])) {
            $this->maxDelay = $value['max_delay'];
            unset($value['max_delay']);
        }
    
        if (isset($value['jitter'])) {
            $this->jitter = $value['jitter'];
            unset($value['jitter']);
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
        if (null !== $this->retryStrategy) {
            $output['retry_strategy'] = $this->retryStrategy;
        }
        if (null !== $this->httpCodes) {
            $output['http_codes'] = array_map(function ($v) { return $v->toArray(); }, $this->httpCodes);
        }
        if (null !== $this->maxRetries) {
            $output['max_retries'] = $this->maxRetries;
        }
        if (null !== $this->delay) {
            $output['delay'] = $this->delay;
        }
        if (null !== $this->multiplier) {
            $output['multiplier'] = $this->multiplier;
        }
        if (null !== $this->maxDelay) {
            $output['max_delay'] = $this->maxDelay;
        }
        if (null !== $this->jitter) {
            $output['jitter'] = $this->jitter;
        }
    
        return $output;
    }
    

}
