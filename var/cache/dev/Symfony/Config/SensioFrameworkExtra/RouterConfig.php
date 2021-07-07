<?php

namespace Symfony\Config\SensioFrameworkExtra;


use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class RouterConfig 
{
    private $annotations;
    
    /**
     * @default true
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function annotations($value): self
    {
        $this->annotations = $value;
    
        return $this;
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['annotations'])) {
            $this->annotations = $value['annotations'];
            unset($value['annotations']);
        }
    
        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }
    
    
    public function toArray(): array
    {
        $output = [];
        if (null !== $this->annotations) {
            $output['annotations'] = $this->annotations;
        }
    
        return $output;
    }
    

}
