<?php

namespace Symfony\Config\Doctrine\Orm\EntityManagerConfig;


use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class DqlConfig 
{
    private $stringFunctions;
    private $numericFunctions;
    private $datetimeFunctions;
    
    /**
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function stringFunction(string $name, $value): self
    {
        $this->stringFunctions[$name] = $value;
    
        return $this;
    }
    
    /**
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function numericFunction(string $name, $value): self
    {
        $this->numericFunctions[$name] = $value;
    
        return $this;
    }
    
    /**
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function datetimeFunction(string $name, $value): self
    {
        $this->datetimeFunctions[$name] = $value;
    
        return $this;
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['string_functions'])) {
            $this->stringFunctions = $value['string_functions'];
            unset($value['string_functions']);
        }
    
        if (isset($value['numeric_functions'])) {
            $this->numericFunctions = $value['numeric_functions'];
            unset($value['numeric_functions']);
        }
    
        if (isset($value['datetime_functions'])) {
            $this->datetimeFunctions = $value['datetime_functions'];
            unset($value['datetime_functions']);
        }
    
        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }
    
    
    public function toArray(): array
    {
        $output = [];
        if (null !== $this->stringFunctions) {
            $output['string_functions'] = $this->stringFunctions;
        }
        if (null !== $this->numericFunctions) {
            $output['numeric_functions'] = $this->numericFunctions;
        }
        if (null !== $this->datetimeFunctions) {
            $output['datetime_functions'] = $this->datetimeFunctions;
        }
    
        return $output;
    }
    

}
