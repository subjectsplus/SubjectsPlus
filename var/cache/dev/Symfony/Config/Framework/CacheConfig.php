<?php

namespace Symfony\Config\Framework;

require_once __DIR__.\DIRECTORY_SEPARATOR.'Cache'.\DIRECTORY_SEPARATOR.'PoolConfig.php';

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class CacheConfig 
{
    private $prefixSeed;
    private $app;
    private $system;
    private $directory;
    private $defaultDoctrineProvider;
    private $defaultPsr6Provider;
    private $defaultRedisProvider;
    private $defaultMemcachedProvider;
    private $defaultPdoProvider;
    private $pools;
    
    /**
     * Used to namespace cache keys when using several apps with the same shared backend
     * @example my-application-name/%kernel.environment%
     * @default '_%kernel.project_dir%.%kernel.container_class%'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function prefixSeed($value): self
    {
        $this->prefixSeed = $value;
    
        return $this;
    }
    
    /**
     * App related cache pools configuration
     * @default 'cache.adapter.filesystem'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function app($value): self
    {
        $this->app = $value;
    
        return $this;
    }
    
    /**
     * System related cache pools configuration
     * @default 'cache.adapter.system'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function system($value): self
    {
        $this->system = $value;
    
        return $this;
    }
    
    /**
     * @default '%kernel.cache_dir%/pools'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function directory($value): self
    {
        $this->directory = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function defaultDoctrineProvider($value): self
    {
        $this->defaultDoctrineProvider = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function defaultPsr6Provider($value): self
    {
        $this->defaultPsr6Provider = $value;
    
        return $this;
    }
    
    /**
     * @default 'redis://localhost'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function defaultRedisProvider($value): self
    {
        $this->defaultRedisProvider = $value;
    
        return $this;
    }
    
    /**
     * @default 'memcached://localhost'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function defaultMemcachedProvider($value): self
    {
        $this->defaultMemcachedProvider = $value;
    
        return $this;
    }
    
    /**
     * @default 'database_connection'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function defaultPdoProvider($value): self
    {
        $this->defaultPdoProvider = $value;
    
        return $this;
    }
    
    public function pool(string $name, array $value = []): \Symfony\Config\Framework\Cache\PoolConfig
    {
        if (!isset($this->pools[$name])) {
            return $this->pools[$name] = new \Symfony\Config\Framework\Cache\PoolConfig($value);
        }
        if ([] === $value) {
            return $this->pools[$name];
        }
    
        throw new InvalidConfigurationException('The node created by "pool()" has already been initialized. You cannot pass values the second time you call pool().');
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['prefix_seed'])) {
            $this->prefixSeed = $value['prefix_seed'];
            unset($value['prefix_seed']);
        }
    
        if (isset($value['app'])) {
            $this->app = $value['app'];
            unset($value['app']);
        }
    
        if (isset($value['system'])) {
            $this->system = $value['system'];
            unset($value['system']);
        }
    
        if (isset($value['directory'])) {
            $this->directory = $value['directory'];
            unset($value['directory']);
        }
    
        if (isset($value['default_doctrine_provider'])) {
            $this->defaultDoctrineProvider = $value['default_doctrine_provider'];
            unset($value['default_doctrine_provider']);
        }
    
        if (isset($value['default_psr6_provider'])) {
            $this->defaultPsr6Provider = $value['default_psr6_provider'];
            unset($value['default_psr6_provider']);
        }
    
        if (isset($value['default_redis_provider'])) {
            $this->defaultRedisProvider = $value['default_redis_provider'];
            unset($value['default_redis_provider']);
        }
    
        if (isset($value['default_memcached_provider'])) {
            $this->defaultMemcachedProvider = $value['default_memcached_provider'];
            unset($value['default_memcached_provider']);
        }
    
        if (isset($value['default_pdo_provider'])) {
            $this->defaultPdoProvider = $value['default_pdo_provider'];
            unset($value['default_pdo_provider']);
        }
    
        if (isset($value['pools'])) {
            $this->pools = array_map(function ($v) { return new \Symfony\Config\Framework\Cache\PoolConfig($v); }, $value['pools']);
            unset($value['pools']);
        }
    
        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }
    
    
    public function toArray(): array
    {
        $output = [];
        if (null !== $this->prefixSeed) {
            $output['prefix_seed'] = $this->prefixSeed;
        }
        if (null !== $this->app) {
            $output['app'] = $this->app;
        }
        if (null !== $this->system) {
            $output['system'] = $this->system;
        }
        if (null !== $this->directory) {
            $output['directory'] = $this->directory;
        }
        if (null !== $this->defaultDoctrineProvider) {
            $output['default_doctrine_provider'] = $this->defaultDoctrineProvider;
        }
        if (null !== $this->defaultPsr6Provider) {
            $output['default_psr6_provider'] = $this->defaultPsr6Provider;
        }
        if (null !== $this->defaultRedisProvider) {
            $output['default_redis_provider'] = $this->defaultRedisProvider;
        }
        if (null !== $this->defaultMemcachedProvider) {
            $output['default_memcached_provider'] = $this->defaultMemcachedProvider;
        }
        if (null !== $this->defaultPdoProvider) {
            $output['default_pdo_provider'] = $this->defaultPdoProvider;
        }
        if (null !== $this->pools) {
            $output['pools'] = array_map(function ($v) { return $v->toArray(); }, $this->pools);
        }
    
        return $output;
    }
    

}
