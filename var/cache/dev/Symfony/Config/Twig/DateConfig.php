<?php

namespace Symfony\Config\Twig;


use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class DateConfig 
{
    private $format;
    private $intervalFormat;
    private $timezone;
    
    /**
     * @default 'F j, Y H:i'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function format($value): self
    {
        $this->format = $value;
    
        return $this;
    }
    
    /**
     * @default '%d days'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function intervalFormat($value): self
    {
        $this->intervalFormat = $value;
    
        return $this;
    }
    
    /**
     * The timezone used when formatting dates, when set to null, the timezone returned by date_default_timezone_get() is used
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function timezone($value): self
    {
        $this->timezone = $value;
    
        return $this;
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['format'])) {
            $this->format = $value['format'];
            unset($value['format']);
        }
    
        if (isset($value['interval_format'])) {
            $this->intervalFormat = $value['interval_format'];
            unset($value['interval_format']);
        }
    
        if (isset($value['timezone'])) {
            $this->timezone = $value['timezone'];
            unset($value['timezone']);
        }
    
        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }
    
    
    public function toArray(): array
    {
        $output = [];
        if (null !== $this->format) {
            $output['format'] = $this->format;
        }
        if (null !== $this->intervalFormat) {
            $output['interval_format'] = $this->intervalFormat;
        }
        if (null !== $this->timezone) {
            $output['timezone'] = $this->timezone;
        }
    
        return $output;
    }
    

}
