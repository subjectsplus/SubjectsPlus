<?php

namespace Symfony\Config\Framework\Cache;


use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class PoolConfig 
{
    private $adapters;
    private $tags;
    private $public;
    private $defaultLifetime;
    private $provider;
    private $earlyExpirationMessageBus;
    private $clearer;
    
    /**
     * @param ParamConfigurator|list<mixed|ParamConfigurator> $value
     * @return $this
     */
    public function adapters($value): self
    {
        $this->adapters = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function tags($value): self
    {
        $this->tags = $value;
    
        return $this;
    }
    
    /**
     * @default false
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function public($value): self
    {
        $this->public = $value;
    
        return $this;
    }
    
    /**
     * Default lifetime of the pool
     * @example "600" for 5 minutes expressed in seconds, "PT5M" for five minutes expressed as ISO 8601 time interval, or "5 minutes" as a date expression
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function defaultLifetime($value): self
    {
        $this->defaultLifetime = $value;
    
        return $this;
    }
    
    /**
     * Overwrite the setting from the default provider for this adapter.
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function provider($value): self
    {
        $this->provider = $value;
    
        return $this;
    }
    
    /**
     * @example "messenger.default_bus" to send early expiration events to the default Messenger bus.
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function earlyExpirationMessageBus($value): self
    {
        $this->earlyExpirationMessageBus = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function clearer($value): self
    {
        $this->clearer = $value;
    
        return $this;
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['adapters'])) {
            $this->adapters = $value['adapters'];
            unset($value['adapters']);
        }
    
        if (isset($value['tags'])) {
            $this->tags = $value['tags'];
            unset($value['tags']);
        }
    
        if (isset($value['public'])) {
            $this->public = $value['public'];
            unset($value['public']);
        }
    
        if (isset($value['default_lifetime'])) {
            $this->defaultLifetime = $value['default_lifetime'];
            unset($value['default_lifetime']);
        }
    
        if (isset($value['provider'])) {
            $this->provider = $value['provider'];
            unset($value['provider']);
        }
    
        if (isset($value['early_expiration_message_bus'])) {
            $this->earlyExpirationMessageBus = $value['early_expiration_message_bus'];
            unset($value['early_expiration_message_bus']);
        }
    
        if (isset($value['clearer'])) {
            $this->clearer = $value['clearer'];
            unset($value['clearer']);
        }
    
        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }
    
    
    public function toArray(): array
    {
        $output = [];
        if (null !== $this->adapters) {
            $output['adapters'] = $this->adapters;
        }
        if (null !== $this->tags) {
            $output['tags'] = $this->tags;
        }
        if (null !== $this->public) {
            $output['public'] = $this->public;
        }
        if (null !== $this->defaultLifetime) {
            $output['default_lifetime'] = $this->defaultLifetime;
        }
        if (null !== $this->provider) {
            $output['provider'] = $this->provider;
        }
        if (null !== $this->earlyExpirationMessageBus) {
            $output['early_expiration_message_bus'] = $this->earlyExpirationMessageBus;
        }
        if (null !== $this->clearer) {
            $output['clearer'] = $this->clearer;
        }
    
        return $output;
    }
    

}
