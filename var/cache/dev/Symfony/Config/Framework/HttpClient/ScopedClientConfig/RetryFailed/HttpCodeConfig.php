<?php

namespace Symfony\Config\Framework\HttpClient\ScopedClientConfig\RetryFailed;


use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class HttpCodeConfig 
{
    private $code;
    private $methods;
    
    /**
     * @default null
     * @param ParamConfigurator|int $value
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
    public function methods($value): self
    {
        $this->methods = $value;
    
        return $this;
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['code'])) {
            $this->code = $value['code'];
            unset($value['code']);
        }
    
        if (isset($value['methods'])) {
            $this->methods = $value['methods'];
            unset($value['methods']);
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
        if (null !== $this->methods) {
            $output['methods'] = $this->methods;
        }
    
        return $output;
    }
    

}
