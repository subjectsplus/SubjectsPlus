<?php

namespace Symfony\Config\Monolog\HandlerConfig;


use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class RedisConfig 
{
    private $id;
    private $host;
    private $password;
    private $port;
    private $database;
    private $keyName;
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function id($value): self
    {
        $this->id = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function host($value): self
    {
        $this->host = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function password($value): self
    {
        $this->password = $value;
    
        return $this;
    }
    
    /**
     * @default 6379
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function port($value): self
    {
        $this->port = $value;
    
        return $this;
    }
    
    /**
     * @default 0
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function database($value): self
    {
        $this->database = $value;
    
        return $this;
    }
    
    /**
     * @default 'monolog_redis'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function keyName($value): self
    {
        $this->keyName = $value;
    
        return $this;
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['id'])) {
            $this->id = $value['id'];
            unset($value['id']);
        }
    
        if (isset($value['host'])) {
            $this->host = $value['host'];
            unset($value['host']);
        }
    
        if (isset($value['password'])) {
            $this->password = $value['password'];
            unset($value['password']);
        }
    
        if (isset($value['port'])) {
            $this->port = $value['port'];
            unset($value['port']);
        }
    
        if (isset($value['database'])) {
            $this->database = $value['database'];
            unset($value['database']);
        }
    
        if (isset($value['key_name'])) {
            $this->keyName = $value['key_name'];
            unset($value['key_name']);
        }
    
        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }
    
    
    public function toArray(): array
    {
        $output = [];
        if (null !== $this->id) {
            $output['id'] = $this->id;
        }
        if (null !== $this->host) {
            $output['host'] = $this->host;
        }
        if (null !== $this->password) {
            $output['password'] = $this->password;
        }
        if (null !== $this->port) {
            $output['port'] = $this->port;
        }
        if (null !== $this->database) {
            $output['database'] = $this->database;
        }
        if (null !== $this->keyName) {
            $output['key_name'] = $this->keyName;
        }
    
        return $output;
    }
    

}
