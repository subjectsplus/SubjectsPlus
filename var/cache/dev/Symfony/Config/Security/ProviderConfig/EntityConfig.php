<?php

namespace Symfony\Config\Security\ProviderConfig;


use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class EntityConfig 
{
    private $class;
    private $property;
    private $managerName;
    
    /**
     * The full entity class name of your user class.
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function class($value): self
    {
        $this->class = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function property($value): self
    {
        $this->property = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function managerName($value): self
    {
        $this->managerName = $value;
    
        return $this;
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['class'])) {
            $this->class = $value['class'];
            unset($value['class']);
        }
    
        if (isset($value['property'])) {
            $this->property = $value['property'];
            unset($value['property']);
        }
    
        if (isset($value['manager_name'])) {
            $this->managerName = $value['manager_name'];
            unset($value['manager_name']);
        }
    
        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }
    
    
    public function toArray(): array
    {
        $output = [];
        if (null !== $this->class) {
            $output['class'] = $this->class;
        }
        if (null !== $this->property) {
            $output['property'] = $this->property;
        }
        if (null !== $this->managerName) {
            $output['manager_name'] = $this->managerName;
        }
    
        return $output;
    }
    

}
