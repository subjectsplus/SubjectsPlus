<?php

namespace Symfony\Config\Security\FirewallConfig\Logout;


use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class DeleteCookieConfig 
{
    private $path;
    private $domain;
    private $secure;
    private $samesite;
    
    /**
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
    public function domain($value): self
    {
        $this->domain = $value;
    
        return $this;
    }
    
    /**
     * @default false
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function secure($value): self
    {
        $this->secure = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function samesite($value): self
    {
        $this->samesite = $value;
    
        return $this;
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['path'])) {
            $this->path = $value['path'];
            unset($value['path']);
        }
    
        if (isset($value['domain'])) {
            $this->domain = $value['domain'];
            unset($value['domain']);
        }
    
        if (isset($value['secure'])) {
            $this->secure = $value['secure'];
            unset($value['secure']);
        }
    
        if (isset($value['samesite'])) {
            $this->samesite = $value['samesite'];
            unset($value['samesite']);
        }
    
        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }
    
    
    public function toArray(): array
    {
        $output = [];
        if (null !== $this->path) {
            $output['path'] = $this->path;
        }
        if (null !== $this->domain) {
            $output['domain'] = $this->domain;
        }
        if (null !== $this->secure) {
            $output['secure'] = $this->secure;
        }
        if (null !== $this->samesite) {
            $output['samesite'] = $this->samesite;
        }
    
        return $output;
    }
    

}
