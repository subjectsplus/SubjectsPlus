<?php

namespace Symfony\Config\Framework\Messenger\TransportConfig;


use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class RetryStrategyConfig 
{
    private $service;
    private $maxRetries;
    private $delay;
    private $multiplier;
    private $maxDelay;
    
    /**
     * Service id to override the retry strategy entirely
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function service($value): self
    {
        $this->service = $value;
    
        return $this;
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
     * If greater than 1, delay will grow exponentially for each retry: this delay = (delay * (multiple ^ retries))
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
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['service'])) {
            $this->service = $value['service'];
            unset($value['service']);
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
    
        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }
    
    
    public function toArray(): array
    {
        $output = [];
        if (null !== $this->service) {
            $output['service'] = $this->service;
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
    
        return $output;
    }
    

}
