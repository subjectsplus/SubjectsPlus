<?php

namespace Symfony\Config\Security;


use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class AccessControlConfig 
{
    private $requiresChannel;
    private $path;
    private $host;
    private $port;
    private $ips;
    private $methods;
    private $allowIf;
    private $roles;
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function requiresChannel($value): self
    {
        $this->requiresChannel = $value;
    
        return $this;
    }
    
    /**
     * use the urldecoded format
     * @example ^/path to resource/
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function path($value): self
    {
        $this->path = $value;
    
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
     * @param ParamConfigurator|int $value
     * @return $this
     */
    public function port($value): self
    {
        $this->port = $value;
    
        return $this;
    }
    
    /**
     * @param ParamConfigurator|list<mixed|ParamConfigurator> $value
     * @return $this
     */
    public function ips($value): self
    {
        $this->ips = $value;
    
        return $this;
    }
    
    /**
     * @param ParamConfigurator|list<mixed|ParamConfigurator> $value
     * @return $this
     */
    public function methods($value): self
    {
        $this->methods = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function allowIf($value): self
    {
        $this->allowIf = $value;
    
        return $this;
    }
    
    /**
     * @param ParamConfigurator|list<mixed|ParamConfigurator> $value
     * @return $this
     */
    public function roles($value): self
    {
        $this->roles = $value;
    
        return $this;
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['requires_channel'])) {
            $this->requiresChannel = $value['requires_channel'];
            unset($value['requires_channel']);
        }
    
        if (isset($value['path'])) {
            $this->path = $value['path'];
            unset($value['path']);
        }
    
        if (isset($value['host'])) {
            $this->host = $value['host'];
            unset($value['host']);
        }
    
        if (isset($value['port'])) {
            $this->port = $value['port'];
            unset($value['port']);
        }
    
        if (isset($value['ips'])) {
            $this->ips = $value['ips'];
            unset($value['ips']);
        }
    
        if (isset($value['methods'])) {
            $this->methods = $value['methods'];
            unset($value['methods']);
        }
    
        if (isset($value['allow_if'])) {
            $this->allowIf = $value['allow_if'];
            unset($value['allow_if']);
        }
    
        if (isset($value['roles'])) {
            $this->roles = $value['roles'];
            unset($value['roles']);
        }
    
        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }
    
    
    public function toArray(): array
    {
        $output = [];
        if (null !== $this->requiresChannel) {
            $output['requires_channel'] = $this->requiresChannel;
        }
        if (null !== $this->path) {
            $output['path'] = $this->path;
        }
        if (null !== $this->host) {
            $output['host'] = $this->host;
        }
        if (null !== $this->port) {
            $output['port'] = $this->port;
        }
        if (null !== $this->ips) {
            $output['ips'] = $this->ips;
        }
        if (null !== $this->methods) {
            $output['methods'] = $this->methods;
        }
        if (null !== $this->allowIf) {
            $output['allow_if'] = $this->allowIf;
        }
        if (null !== $this->roles) {
            $output['roles'] = $this->roles;
        }
    
        return $output;
    }
    

}
