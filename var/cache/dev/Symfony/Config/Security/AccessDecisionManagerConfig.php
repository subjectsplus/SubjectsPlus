<?php

namespace Symfony\Config\Security;


use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class AccessDecisionManagerConfig 
{
    private $strategy;
    private $service;
    private $allowIfAllAbstain;
    private $allowIfEqualGrantedDenied;
    
    /**
     * @default null
     * @param ParamConfigurator|'affirmative'|'consensus'|'unanimous'|'priority' $value
     * @return $this
     */
    public function strategy($value): self
    {
        $this->strategy = $value;
    
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
     * @default false
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function allowIfAllAbstain($value): self
    {
        $this->allowIfAllAbstain = $value;
    
        return $this;
    }
    
    /**
     * @default true
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function allowIfEqualGrantedDenied($value): self
    {
        $this->allowIfEqualGrantedDenied = $value;
    
        return $this;
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['strategy'])) {
            $this->strategy = $value['strategy'];
            unset($value['strategy']);
        }
    
        if (isset($value['service'])) {
            $this->service = $value['service'];
            unset($value['service']);
        }
    
        if (isset($value['allow_if_all_abstain'])) {
            $this->allowIfAllAbstain = $value['allow_if_all_abstain'];
            unset($value['allow_if_all_abstain']);
        }
    
        if (isset($value['allow_if_equal_granted_denied'])) {
            $this->allowIfEqualGrantedDenied = $value['allow_if_equal_granted_denied'];
            unset($value['allow_if_equal_granted_denied']);
        }
    
        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }
    
    
    public function toArray(): array
    {
        $output = [];
        if (null !== $this->strategy) {
            $output['strategy'] = $this->strategy;
        }
        if (null !== $this->service) {
            $output['service'] = $this->service;
        }
        if (null !== $this->allowIfAllAbstain) {
            $output['allow_if_all_abstain'] = $this->allowIfAllAbstain;
        }
        if (null !== $this->allowIfEqualGrantedDenied) {
            $output['allow_if_equal_granted_denied'] = $this->allowIfEqualGrantedDenied;
        }
    
        return $output;
    }
    

}
