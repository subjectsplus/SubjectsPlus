<?php

namespace Symfony\Config;


use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class WebProfilerConfig implements \Symfony\Component\Config\Builder\ConfigBuilderInterface
{
    private $toolbar;
    private $interceptRedirects;
    private $excludedAjaxPaths;
    
    /**
     * @default false
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function toolbar($value): self
    {
        $this->toolbar = $value;
    
        return $this;
    }
    
    /**
     * @default false
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function interceptRedirects($value): self
    {
        $this->interceptRedirects = $value;
    
        return $this;
    }
    
    /**
     * @default '^/((index|app(_[\\w]+)?)\\.php/)?_wdt'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function excludedAjaxPaths($value): self
    {
        $this->excludedAjaxPaths = $value;
    
        return $this;
    }
    
    public function getExtensionAlias(): string
    {
        return 'web_profiler';
    }
            
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['toolbar'])) {
            $this->toolbar = $value['toolbar'];
            unset($value['toolbar']);
        }
    
        if (isset($value['intercept_redirects'])) {
            $this->interceptRedirects = $value['intercept_redirects'];
            unset($value['intercept_redirects']);
        }
    
        if (isset($value['excluded_ajax_paths'])) {
            $this->excludedAjaxPaths = $value['excluded_ajax_paths'];
            unset($value['excluded_ajax_paths']);
        }
    
        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }
    
    
    public function toArray(): array
    {
        $output = [];
        if (null !== $this->toolbar) {
            $output['toolbar'] = $this->toolbar;
        }
        if (null !== $this->interceptRedirects) {
            $output['intercept_redirects'] = $this->interceptRedirects;
        }
        if (null !== $this->excludedAjaxPaths) {
            $output['excluded_ajax_paths'] = $this->excludedAjaxPaths;
        }
    
        return $output;
    }
    

}
