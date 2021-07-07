<?php

namespace Symfony\Config\Framework;


use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class HttpCacheConfig 
{
    private $enabled;
    private $debug;
    private $traceLevel;
    private $traceHeader;
    private $defaultTtl;
    private $privateHeaders;
    private $allowReload;
    private $allowRevalidate;
    private $staleWhileRevalidate;
    private $staleIfError;
    
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
     * @default '%kernel.debug%'
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function debug($value): self
    {
        $this->debug = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|'none'|'short'|'full' $value
     * @return $this
     */
    public function traceLevel($value): self
    {
        $this->traceLevel = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function traceHeader($value): self
    {
        $this->traceHeader = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|int $value
     * @return $this
     */
    public function defaultTtl($value): self
    {
        $this->defaultTtl = $value;
    
        return $this;
    }
    
    /**
     * @param ParamConfigurator|list<mixed|ParamConfigurator> $value
     * @return $this
     */
    public function privateHeaders($value): self
    {
        $this->privateHeaders = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function allowReload($value): self
    {
        $this->allowReload = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function allowRevalidate($value): self
    {
        $this->allowRevalidate = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|int $value
     * @return $this
     */
    public function staleWhileRevalidate($value): self
    {
        $this->staleWhileRevalidate = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|int $value
     * @return $this
     */
    public function staleIfError($value): self
    {
        $this->staleIfError = $value;
    
        return $this;
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['enabled'])) {
            $this->enabled = $value['enabled'];
            unset($value['enabled']);
        }
    
        if (isset($value['debug'])) {
            $this->debug = $value['debug'];
            unset($value['debug']);
        }
    
        if (isset($value['trace_level'])) {
            $this->traceLevel = $value['trace_level'];
            unset($value['trace_level']);
        }
    
        if (isset($value['trace_header'])) {
            $this->traceHeader = $value['trace_header'];
            unset($value['trace_header']);
        }
    
        if (isset($value['default_ttl'])) {
            $this->defaultTtl = $value['default_ttl'];
            unset($value['default_ttl']);
        }
    
        if (isset($value['private_headers'])) {
            $this->privateHeaders = $value['private_headers'];
            unset($value['private_headers']);
        }
    
        if (isset($value['allow_reload'])) {
            $this->allowReload = $value['allow_reload'];
            unset($value['allow_reload']);
        }
    
        if (isset($value['allow_revalidate'])) {
            $this->allowRevalidate = $value['allow_revalidate'];
            unset($value['allow_revalidate']);
        }
    
        if (isset($value['stale_while_revalidate'])) {
            $this->staleWhileRevalidate = $value['stale_while_revalidate'];
            unset($value['stale_while_revalidate']);
        }
    
        if (isset($value['stale_if_error'])) {
            $this->staleIfError = $value['stale_if_error'];
            unset($value['stale_if_error']);
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
        if (null !== $this->debug) {
            $output['debug'] = $this->debug;
        }
        if (null !== $this->traceLevel) {
            $output['trace_level'] = $this->traceLevel;
        }
        if (null !== $this->traceHeader) {
            $output['trace_header'] = $this->traceHeader;
        }
        if (null !== $this->defaultTtl) {
            $output['default_ttl'] = $this->defaultTtl;
        }
        if (null !== $this->privateHeaders) {
            $output['private_headers'] = $this->privateHeaders;
        }
        if (null !== $this->allowReload) {
            $output['allow_reload'] = $this->allowReload;
        }
        if (null !== $this->allowRevalidate) {
            $output['allow_revalidate'] = $this->allowRevalidate;
        }
        if (null !== $this->staleWhileRevalidate) {
            $output['stale_while_revalidate'] = $this->staleWhileRevalidate;
        }
        if (null !== $this->staleIfError) {
            $output['stale_if_error'] = $this->staleIfError;
        }
    
        return $output;
    }
    

}
