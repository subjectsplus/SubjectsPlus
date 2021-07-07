<?php

namespace Symfony\Config\Framework;


use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class FragmentsConfig 
{
    private $enabled;
    private $hincludeDefaultTemplate;
    private $path;
    
    /**
     * @default false
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function enabled($value): self
    {
        $this->enabled = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function hincludeDefaultTemplate($value): self
    {
        $this->hincludeDefaultTemplate = $value;
    
        return $this;
    }
    
    /**
     * @default '/_fragment'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function path($value): self
    {
        $this->path = $value;
    
        return $this;
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['enabled'])) {
            $this->enabled = $value['enabled'];
            unset($value['enabled']);
        }
    
        if (isset($value['hinclude_default_template'])) {
            $this->hincludeDefaultTemplate = $value['hinclude_default_template'];
            unset($value['hinclude_default_template']);
        }
    
        if (isset($value['path'])) {
            $this->path = $value['path'];
            unset($value['path']);
        }
    
        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }
    
    
    public function toArray(): array
    {
        $output = [];
        if (null !== $this->enabled) {
            $output['enabled'] = $this->enabled;
        }
        if (null !== $this->hincludeDefaultTemplate) {
            $output['hinclude_default_template'] = $this->hincludeDefaultTemplate;
        }
        if (null !== $this->path) {
            $output['path'] = $this->path;
        }
    
        return $output;
    }
    

}
