<?php

namespace Symfony\Config\Monolog\HandlerConfig;


use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class ExcludedHttpCodeConfig 
{
    private $code;
    private $urls;
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function code($value): self
    {
        $this->code = $value;
    
        return $this;
    }
    
    /**
     * @param ParamConfigurator|list<mixed|ParamConfigurator> $value
     * @return $this
     */
    public function urls($value): self
    {
        $this->urls = $value;
    
        return $this;
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['code'])) {
            $this->code = $value['code'];
            unset($value['code']);
        }
    
        if (isset($value['urls'])) {
            $this->urls = $value['urls'];
            unset($value['urls']);
        }
    
        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }
    
    
    public function toArray(): array
    {
        $output = [];
        if (null !== $this->code) {
            $output['code'] = $this->code;
        }
        if (null !== $this->urls) {
            $output['urls'] = $this->urls;
        }
    
        return $output;
    }
    

}
