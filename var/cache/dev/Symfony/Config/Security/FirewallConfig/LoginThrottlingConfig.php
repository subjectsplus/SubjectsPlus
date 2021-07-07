<?php

namespace Symfony\Config\Security\FirewallConfig;


use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class LoginThrottlingConfig 
{
    private $limiter;
    private $maxAttempts;
    private $interval;
    private $lockFactory;
    
    /**
     * A service id implementing "Symfony\Component\HttpFoundation\RateLimiter\RequestRateLimiterInterface".
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function limiter($value): self
    {
        $this->limiter = $value;
    
        return $this;
    }
    
    /**
     * @default 5
     * @param ParamConfigurator|int $value
     * @return $this
     */
    public function maxAttempts($value): self
    {
        $this->maxAttempts = $value;
    
        return $this;
    }
    
    /**
     * @default '1 minute'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function interval($value): self
    {
        $this->interval = $value;
    
        return $this;
    }
    
    /**
     * The service ID of the lock factory used by the login rate limiter (or null to disable locking)
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function lockFactory($value): self
    {
        $this->lockFactory = $value;
    
        return $this;
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['limiter'])) {
            $this->limiter = $value['limiter'];
            unset($value['limiter']);
        }
    
        if (isset($value['max_attempts'])) {
            $this->maxAttempts = $value['max_attempts'];
            unset($value['max_attempts']);
        }
    
        if (isset($value['interval'])) {
            $this->interval = $value['interval'];
            unset($value['interval']);
        }
    
        if (isset($value['lock_factory'])) {
            $this->lockFactory = $value['lock_factory'];
            unset($value['lock_factory']);
        }
    
        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }
    
    
    public function toArray(): array
    {
        $output = [];
        if (null !== $this->limiter) {
            $output['limiter'] = $this->limiter;
        }
        if (null !== $this->maxAttempts) {
            $output['max_attempts'] = $this->maxAttempts;
        }
        if (null !== $this->interval) {
            $output['interval'] = $this->interval;
        }
        if (null !== $this->lockFactory) {
            $output['lock_factory'] = $this->lockFactory;
        }
    
        return $output;
    }
    

}
