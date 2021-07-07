<?php

namespace Symfony\Config\Framework\RateLimiter;

require_once __DIR__.\DIRECTORY_SEPARATOR.'LimiterConfig'.\DIRECTORY_SEPARATOR.'RateConfig.php';

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class LimiterConfig 
{
    private $lockFactory;
    private $cachePool;
    private $storageService;
    private $policy;
    private $limit;
    private $interval;
    private $rate;
    
    /**
     * The service ID of the lock factory used by this limiter (or null to disable locking)
     * @default 'lock.factory'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function lockFactory($value): self
    {
        $this->lockFactory = $value;
    
        return $this;
    }
    
    /**
     * The cache pool to use for storing the current limiter state
     * @default 'cache.rate_limiter'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function cachePool($value): self
    {
        $this->cachePool = $value;
    
        return $this;
    }
    
    /**
     * The service ID of a custom storage implementation, this precedes any configured "cache_pool"
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function storageService($value): self
    {
        $this->storageService = $value;
    
        return $this;
    }
    
    /**
     * The algorithm to be used by this limiter
     * @default null
     * @param ParamConfigurator|'fixed_window'|'token_bucket'|'sliding_window'|'no_limit' $value
     * @return $this
     */
    public function policy($value): self
    {
        $this->policy = $value;
    
        return $this;
    }
    
    /**
     * The maximum allowed hits in a fixed interval or burst
     * @default null
     * @param ParamConfigurator|int $value
     * @return $this
     */
    public function limit($value): self
    {
        $this->limit = $value;
    
        return $this;
    }
    
    /**
     * Configures the fixed interval if "policy" is set to "fixed_window" or "sliding_window". The value must be a number followed by "second", "minute", "hour", "day", "week" or "month" (or their plural equivalent).
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function interval($value): self
    {
        $this->interval = $value;
    
        return $this;
    }
    
    public function rate(array $value = []): \Symfony\Config\Framework\RateLimiter\LimiterConfig\RateConfig
    {
        if (null === $this->rate) {
            $this->rate = new \Symfony\Config\Framework\RateLimiter\LimiterConfig\RateConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "rate()" has already been initialized. You cannot pass values the second time you call rate().');
        }
    
        return $this->rate;
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['lock_factory'])) {
            $this->lockFactory = $value['lock_factory'];
            unset($value['lock_factory']);
        }
    
        if (isset($value['cache_pool'])) {
            $this->cachePool = $value['cache_pool'];
            unset($value['cache_pool']);
        }
    
        if (isset($value['storage_service'])) {
            $this->storageService = $value['storage_service'];
            unset($value['storage_service']);
        }
    
        if (isset($value['policy'])) {
            $this->policy = $value['policy'];
            unset($value['policy']);
        }
    
        if (isset($value['limit'])) {
            $this->limit = $value['limit'];
            unset($value['limit']);
        }
    
        if (isset($value['interval'])) {
            $this->interval = $value['interval'];
            unset($value['interval']);
        }
    
        if (isset($value['rate'])) {
            $this->rate = new \Symfony\Config\Framework\RateLimiter\LimiterConfig\RateConfig($value['rate']);
            unset($value['rate']);
        }
    
        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }
    
    
    public function toArray(): array
    {
        $output = [];
        if (null !== $this->lockFactory) {
            $output['lock_factory'] = $this->lockFactory;
        }
        if (null !== $this->cachePool) {
            $output['cache_pool'] = $this->cachePool;
        }
        if (null !== $this->storageService) {
            $output['storage_service'] = $this->storageService;
        }
        if (null !== $this->policy) {
            $output['policy'] = $this->policy;
        }
        if (null !== $this->limit) {
            $output['limit'] = $this->limit;
        }
        if (null !== $this->interval) {
            $output['interval'] = $this->interval;
        }
        if (null !== $this->rate) {
            $output['rate'] = $this->rate->toArray();
        }
    
        return $output;
    }
    

}
