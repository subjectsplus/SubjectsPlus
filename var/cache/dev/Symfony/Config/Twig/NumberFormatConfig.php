<?php

namespace Symfony\Config\Twig;


use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class NumberFormatConfig 
{
    private $decimals;
    private $decimalPoint;
    private $thousandsSeparator;
    
    /**
     * @default 0
     * @param ParamConfigurator|int $value
     * @return $this
     */
    public function decimals($value): self
    {
        $this->decimals = $value;
    
        return $this;
    }
    
    /**
     * @default '.'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function decimalPoint($value): self
    {
        $this->decimalPoint = $value;
    
        return $this;
    }
    
    /**
     * @default ','
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function thousandsSeparator($value): self
    {
        $this->thousandsSeparator = $value;
    
        return $this;
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['decimals'])) {
            $this->decimals = $value['decimals'];
            unset($value['decimals']);
        }
    
        if (isset($value['decimal_point'])) {
            $this->decimalPoint = $value['decimal_point'];
            unset($value['decimal_point']);
        }
    
        if (isset($value['thousands_separator'])) {
            $this->thousandsSeparator = $value['thousands_separator'];
            unset($value['thousands_separator']);
        }
    
        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }
    
    
    public function toArray(): array
    {
        $output = [];
        if (null !== $this->decimals) {
            $output['decimals'] = $this->decimals;
        }
        if (null !== $this->decimalPoint) {
            $output['decimal_point'] = $this->decimalPoint;
        }
        if (null !== $this->thousandsSeparator) {
            $output['thousands_separator'] = $this->thousandsSeparator;
        }
    
        return $output;
    }
    

}
