<?php

namespace Symfony\Config\Framework;


use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class RouterConfig 
{
    private $enabled;
    private $resource;
    private $type;
    private $defaultUri;
    private $httpPort;
    private $httpsPort;
    private $strictRequirements;
    private $utf8;
    
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
    public function resource($value): self
    {
        $this->resource = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function type($value): self
    {
        $this->type = $value;
    
        return $this;
    }
    
    /**
     * The default URI used to generate URLs in a non-HTTP context
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function defaultUri($value): self
    {
        $this->defaultUri = $value;
    
        return $this;
    }
    
    /**
     * @default 80
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function httpPort($value): self
    {
        $this->httpPort = $value;
    
        return $this;
    }
    
    /**
     * @default 443
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function httpsPort($value): self
    {
        $this->httpsPort = $value;
    
        return $this;
    }
    
    /**
     * set to true to throw an exception when a parameter does not match the requirements
    set to false to disable exceptions when a parameter does not match the requirements (and return null instead)
    set to null to disable parameter checks against requirements
    'true' is the preferred configuration in development mode, while 'false' or 'null' might be preferred in production
     * @default true
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function strictRequirements($value): self
    {
        $this->strictRequirements = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function utf8($value): self
    {
        $this->utf8 = $value;
    
        return $this;
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['enabled'])) {
            $this->enabled = $value['enabled'];
            unset($value['enabled']);
        }
    
        if (isset($value['resource'])) {
            $this->resource = $value['resource'];
            unset($value['resource']);
        }
    
        if (isset($value['type'])) {
            $this->type = $value['type'];
            unset($value['type']);
        }
    
        if (isset($value['default_uri'])) {
            $this->defaultUri = $value['default_uri'];
            unset($value['default_uri']);
        }
    
        if (isset($value['http_port'])) {
            $this->httpPort = $value['http_port'];
            unset($value['http_port']);
        }
    
        if (isset($value['https_port'])) {
            $this->httpsPort = $value['https_port'];
            unset($value['https_port']);
        }
    
        if (isset($value['strict_requirements'])) {
            $this->strictRequirements = $value['strict_requirements'];
            unset($value['strict_requirements']);
        }
    
        if (isset($value['utf8'])) {
            $this->utf8 = $value['utf8'];
            unset($value['utf8']);
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
        if (null !== $this->resource) {
            $output['resource'] = $this->resource;
        }
        if (null !== $this->type) {
            $output['type'] = $this->type;
        }
        if (null !== $this->defaultUri) {
            $output['default_uri'] = $this->defaultUri;
        }
        if (null !== $this->httpPort) {
            $output['http_port'] = $this->httpPort;
        }
        if (null !== $this->httpsPort) {
            $output['https_port'] = $this->httpsPort;
        }
        if (null !== $this->strictRequirements) {
            $output['strict_requirements'] = $this->strictRequirements;
        }
        if (null !== $this->utf8) {
            $output['utf8'] = $this->utf8;
        }
    
        return $output;
    }
    

}
