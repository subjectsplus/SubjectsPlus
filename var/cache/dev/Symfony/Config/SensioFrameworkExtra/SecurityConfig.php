<?php

namespace Symfony\Config\SensioFrameworkExtra;


use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class SecurityConfig 
{
    private $annotations;
    private $expressionLanguage;
    
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
    
    /**
     * @default 'sensio_framework_extra.security.expression_language.default'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function expressionLanguage($value): self
    {
        $this->expressionLanguage = $value;
    
        return $this;
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['annotations'])) {
            $this->annotations = $value['annotations'];
            unset($value['annotations']);
        }
    
        if (isset($value['expression_language'])) {
            $this->expressionLanguage = $value['expression_language'];
            unset($value['expression_language']);
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
        if (null !== $this->expressionLanguage) {
            $output['expression_language'] = $this->expressionLanguage;
        }
    
        return $output;
    }
    

}
