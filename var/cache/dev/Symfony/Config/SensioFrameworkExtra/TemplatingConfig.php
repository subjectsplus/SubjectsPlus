<?php

namespace Symfony\Config\SensioFrameworkExtra;


use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class TemplatingConfig 
{
    private $controllerPatterns;
    
    /**
     * @param ParamConfigurator|list<mixed|ParamConfigurator> $value
     * @return $this
     */
    public function controllerPatterns($value): self
    {
        $this->controllerPatterns = $value;
    
        return $this;
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['controller_patterns'])) {
            $this->controllerPatterns = $value['controller_patterns'];
            unset($value['controller_patterns']);
        }
    
        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }
    
    
    public function toArray(): array
    {
        $output = [];
        if (null !== $this->controllerPatterns) {
            $output['controller_patterns'] = $this->controllerPatterns;
        }
    
        return $output;
    }
    

}
