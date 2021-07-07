<?php

namespace Symfony\Config\Doctrine\Orm\EntityManagerConfig;

require_once __DIR__.\DIRECTORY_SEPARATOR.'SecondLevelCache'.\DIRECTORY_SEPARATOR.'RegionCacheDriverConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'SecondLevelCache'.\DIRECTORY_SEPARATOR.'RegionConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'SecondLevelCache'.\DIRECTORY_SEPARATOR.'LoggerConfig.php';

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Loader\ParamConfigurator;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class SecondLevelCacheConfig 
{
    private $regionCacheDriver;
    private $regionLockLifetime;
    private $logEnabled;
    private $regionLifetime;
    private $enabled;
    private $factory;
    private $regions;
    private $loggers;
    
    public function regionCacheDriver(array $value = []): \Symfony\Config\Doctrine\Orm\EntityManagerConfig\SecondLevelCache\RegionCacheDriverConfig
    {
        if (null === $this->regionCacheDriver) {
            $this->regionCacheDriver = new \Symfony\Config\Doctrine\Orm\EntityManagerConfig\SecondLevelCache\RegionCacheDriverConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "regionCacheDriver()" has already been initialized. You cannot pass values the second time you call regionCacheDriver().');
        }
    
        return $this->regionCacheDriver;
    }
    
    /**
     * @default 60
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function regionLockLifetime($value): self
    {
        $this->regionLockLifetime = $value;
    
        return $this;
    }
    
    /**
     * @default true
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function logEnabled($value): self
    {
        $this->logEnabled = $value;
    
        return $this;
    }
    
    /**
     * @default 3600
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function regionLifetime($value): self
    {
        $this->regionLifetime = $value;
    
        return $this;
    }
    
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
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function factory($value): self
    {
        $this->factory = $value;
    
        return $this;
    }
    
    public function region(string $name, array $value = []): \Symfony\Config\Doctrine\Orm\EntityManagerConfig\SecondLevelCache\RegionConfig
    {
        if (!isset($this->regions[$name])) {
            return $this->regions[$name] = new \Symfony\Config\Doctrine\Orm\EntityManagerConfig\SecondLevelCache\RegionConfig($value);
        }
        if ([] === $value) {
            return $this->regions[$name];
        }
    
        throw new InvalidConfigurationException('The node created by "region()" has already been initialized. You cannot pass values the second time you call region().');
    }
    
    public function logger(string $name, array $value = []): \Symfony\Config\Doctrine\Orm\EntityManagerConfig\SecondLevelCache\LoggerConfig
    {
        if (!isset($this->loggers[$name])) {
            return $this->loggers[$name] = new \Symfony\Config\Doctrine\Orm\EntityManagerConfig\SecondLevelCache\LoggerConfig($value);
        }
        if ([] === $value) {
            return $this->loggers[$name];
        }
    
        throw new InvalidConfigurationException('The node created by "logger()" has already been initialized. You cannot pass values the second time you call logger().');
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['region_cache_driver'])) {
            $this->regionCacheDriver = new \Symfony\Config\Doctrine\Orm\EntityManagerConfig\SecondLevelCache\RegionCacheDriverConfig($value['region_cache_driver']);
            unset($value['region_cache_driver']);
        }
    
        if (isset($value['region_lock_lifetime'])) {
            $this->regionLockLifetime = $value['region_lock_lifetime'];
            unset($value['region_lock_lifetime']);
        }
    
        if (isset($value['log_enabled'])) {
            $this->logEnabled = $value['log_enabled'];
            unset($value['log_enabled']);
        }
    
        if (isset($value['region_lifetime'])) {
            $this->regionLifetime = $value['region_lifetime'];
            unset($value['region_lifetime']);
        }
    
        if (isset($value['enabled'])) {
            $this->enabled = $value['enabled'];
            unset($value['enabled']);
        }
    
        if (isset($value['factory'])) {
            $this->factory = $value['factory'];
            unset($value['factory']);
        }
    
        if (isset($value['regions'])) {
            $this->regions = array_map(function ($v) { return new \Symfony\Config\Doctrine\Orm\EntityManagerConfig\SecondLevelCache\RegionConfig($v); }, $value['regions']);
            unset($value['regions']);
        }
    
        if (isset($value['loggers'])) {
            $this->loggers = array_map(function ($v) { return new \Symfony\Config\Doctrine\Orm\EntityManagerConfig\SecondLevelCache\LoggerConfig($v); }, $value['loggers']);
            unset($value['loggers']);
        }
    
        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }
    
    
    public function toArray(): array
    {
        $output = [];
        if (null !== $this->regionCacheDriver) {
            $output['region_cache_driver'] = $this->regionCacheDriver->toArray();
        }
        if (null !== $this->regionLockLifetime) {
            $output['region_lock_lifetime'] = $this->regionLockLifetime;
        }
        if (null !== $this->logEnabled) {
            $output['log_enabled'] = $this->logEnabled;
        }
        if (null !== $this->regionLifetime) {
            $output['region_lifetime'] = $this->regionLifetime;
        }
        if (null !== $this->enabled) {
            $output['enabled'] = $this->enabled;
        }
        if (null !== $this->factory) {
            $output['factory'] = $this->factory;
        }
        if (null !== $this->regions) {
            $output['regions'] = array_map(function ($v) { return $v->toArray(); }, $this->regions);
        }
        if (null !== $this->loggers) {
            $output['loggers'] = array_map(function ($v) { return $v->toArray(); }, $this->loggers);
        }
    
        return $output;
    }
    

}
