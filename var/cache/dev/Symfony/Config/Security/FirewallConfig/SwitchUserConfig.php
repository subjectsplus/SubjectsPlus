<?php

namespace Symfony\Config\Security\FirewallConfig;


use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class SwitchUserConfig 
{
    private $provider;
    private $parameter;
    private $role;
    
    /**
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
     * @default '_switch_user'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function parameter($value): self
    {
        $this->parameter = $value;
    
        return $this;
    }
    
    /**
     * @default 'ROLE_ALLOWED_TO_SWITCH'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function role($value): self
    {
        $this->role = $value;
    
        return $this;
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['provider'])) {
            $this->provider = $value['provider'];
            unset($value['provider']);
        }
    
        if (isset($value['parameter'])) {
            $this->parameter = $value['parameter'];
            unset($value['parameter']);
        }
    
        if (isset($value['role'])) {
            $this->role = $value['role'];
            unset($value['role']);
        }
    
        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }
    
    
    public function toArray(): array
    {
        $output = [];
        if (null !== $this->provider) {
            $output['provider'] = $this->provider;
        }
        if (null !== $this->parameter) {
            $output['parameter'] = $this->parameter;
        }
        if (null !== $this->role) {
            $output['role'] = $this->role;
        }
    
        return $output;
    }
    

}
