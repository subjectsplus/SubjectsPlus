<?php

namespace Symfony\Config\SensioFrameworkExtra;


use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class RequestConfig 
{
    private $converters;
    private $autoConvert;
    private $disable;
    
    /**
     * @default true
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function converters($value): self
    {
        $this->converters = $value;
    
        return $this;
    }
    
    /**
     * @default true
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function autoConvert($value): self
    {
        $this->autoConvert = $value;
    
        return $this;
    }
    
    /**
     * @param ParamConfigurator|list<mixed|ParamConfigurator> $value
     * @return $this
     */
    public function disable($value): self
    {
        $this->disable = $value;
    
        return $this;
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['converters'])) {
            $this->converters = $value['converters'];
            unset($value['converters']);
        }
    
        if (isset($value['auto_convert'])) {
            $this->autoConvert = $value['auto_convert'];
            unset($value['auto_convert']);
        }
    
        if (isset($value['disable'])) {
            $this->disable = $value['disable'];
            unset($value['disable']);
        }
    
        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }
    
    
    public function toArray(): array
    {
        $output = [];
        if (null !== $this->converters) {
            $output['converters'] = $this->converters;
        }
        if (null !== $this->autoConvert) {
            $output['auto_convert'] = $this->autoConvert;
        }
        if (null !== $this->disable) {
            $output['disable'] = $this->disable;
        }
    
        return $output;
    }
    

}
