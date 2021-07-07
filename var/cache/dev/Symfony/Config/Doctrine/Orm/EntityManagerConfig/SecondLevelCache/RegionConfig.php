<?php

namespace Symfony\Config\Doctrine\Orm\EntityManagerConfig\SecondLevelCache;

require_once __DIR__.\DIRECTORY_SEPARATOR.'RegionConfig'.\DIRECTORY_SEPARATOR.'CacheDriverConfig.php';

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Loader\ParamConfigurator;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class RegionConfig 
{
    private $cacheDriver;
    private $lockPath;
    private $lockLifetime;
    private $type;
    private $lifetime;
    private $service;
    private $name;
    
    public function cacheDriver(array $value = []): \Symfony\Config\Doctrine\Orm\EntityManagerConfig\SecondLevelCache\RegionConfig\CacheDriverConfig
    {
        if (null === $this->cacheDriver) {
            $this->cacheDriver = new \Symfony\Config\Doctrine\Orm\EntityManagerConfig\SecondLevelCache\RegionConfig\CacheDriverConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "cacheDriver()" has already been initialized. You cannot pass values the second time you call cacheDriver().');
        }
    
        return $this->cacheDriver;
    }
    
    /**
     * @default '%kernel.cache_dir%/doctrine/orm/slc/filelock'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function lockPath($value): self
    {
        $this->lockPath = $value;
    
        return $this;
    }
    
    /**
     * @default 60
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function lockLifetime($value): self
    {
        $this->lockLifetime = $value;
    
        return $this;
    }
    
    /**
     * @default 'default'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function type($value): self
    {
        $this->type = $value;
    
        return $this;
    }
    
    /**
     * @default 0
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function lifetime($value): self
    {
        $this->lifetime = $value;
    
        return $this;
    }
    
    /**
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
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function name($value): self
    {
        $this->name = $value;
    
        return $this;
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['cache_driver'])) {
            $this->cacheDriver = new \Symfony\Config\Doctrine\Orm\EntityManagerConfig\SecondLevelCache\RegionConfig\CacheDriverConfig($value['cache_driver']);
            unset($value['cache_driver']);
        }
    
        if (isset($value['lock_path'])) {
            $this->lockPath = $value['lock_path'];
            unset($value['lock_path']);
        }
    
        if (isset($value['lock_lifetime'])) {
            $this->lockLifetime = $value['lock_lifetime'];
            unset($value['lock_lifetime']);
        }
    
        if (isset($value['type'])) {
            $this->type = $value['type'];
            unset($value['type']);
        }
    
        if (isset($value['lifetime'])) {
            $this->lifetime = $value['lifetime'];
            unset($value['lifetime']);
        }
    
        if (isset($value['service'])) {
            $this->service = $value['service'];
            unset($value['service']);
        }
    
        if (isset($value['name'])) {
            $this->name = $value['name'];
            unset($value['name']);
        }
    
        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }
    
    
    public function toArray(): array
    {
        $output = [];
        if (null !== $this->cacheDriver) {
            $output['cache_driver'] = $this->cacheDriver->toArray();
        }
        if (null !== $this->lockPath) {
            $output['lock_path'] = $this->lockPath;
        }
        if (null !== $this->lockLifetime) {
            $output['lock_lifetime'] = $this->lockLifetime;
        }
        if (null !== $this->type) {
            $output['type'] = $this->type;
        }
        if (null !== $this->lifetime) {
            $output['lifetime'] = $this->lifetime;
        }
        if (null !== $this->service) {
            $output['service'] = $this->service;
        }
        if (null !== $this->name) {
            $output['name'] = $this->name;
        }
    
        return $output;
    }
    

}
