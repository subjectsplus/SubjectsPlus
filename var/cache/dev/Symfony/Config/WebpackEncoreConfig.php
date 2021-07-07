<?php

namespace Symfony\Config;


use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class WebpackEncoreConfig implements \Symfony\Component\Config\Builder\ConfigBuilderInterface
{
    private $outputPath;
    private $crossorigin;
    private $preload;
    private $cache;
    private $strictMode;
    private $builds;
    private $scriptAttributes;
    private $linkAttributes;
    
    /**
     * The path where Encore is building the assets - i.e. Encore.setOutputPath()
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function outputPath($value): self
    {
        $this->outputPath = $value;
    
        return $this;
    }
    
    /**
     * crossorigin value when Encore.enableIntegrityHashes() is used, can be false (default), anonymous or use-credentials
     * @default false
     * @param ParamConfigurator|false|'anonymous'|'use-credentials' $value
     * @return $this
     */
    public function crossorigin($value): self
    {
        $this->crossorigin = $value;
    
        return $this;
    }
    
    /**
     * preload all rendered script and link tags automatically via the http2 Link header.
     * @default false
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function preload($value): self
    {
        $this->preload = $value;
    
        return $this;
    }
    
    /**
     * Enable caching of the entry point file(s)
     * @default false
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function cache($value): self
    {
        $this->cache = $value;
    
        return $this;
    }
    
    /**
     * Throw an exception if the entrypoints.json file is missing or an entry is missing from the data
     * @default true
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function strictMode($value): self
    {
        $this->strictMode = $value;
    
        return $this;
    }
    
    /**
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function builds(string $name, $value): self
    {
        $this->builds[$name] = $value;
    
        return $this;
    }
    
    /**
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function scriptAttributes(string $name, $value): self
    {
        $this->scriptAttributes[$name] = $value;
    
        return $this;
    }
    
    /**
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function linkAttributes(string $name, $value): self
    {
        $this->linkAttributes[$name] = $value;
    
        return $this;
    }
    
    public function getExtensionAlias(): string
    {
        return 'webpack_encore';
    }
            
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['output_path'])) {
            $this->outputPath = $value['output_path'];
            unset($value['output_path']);
        }
    
        if (isset($value['crossorigin'])) {
            $this->crossorigin = $value['crossorigin'];
            unset($value['crossorigin']);
        }
    
        if (isset($value['preload'])) {
            $this->preload = $value['preload'];
            unset($value['preload']);
        }
    
        if (isset($value['cache'])) {
            $this->cache = $value['cache'];
            unset($value['cache']);
        }
    
        if (isset($value['strict_mode'])) {
            $this->strictMode = $value['strict_mode'];
            unset($value['strict_mode']);
        }
    
        if (isset($value['builds'])) {
            $this->builds = $value['builds'];
            unset($value['builds']);
        }
    
        if (isset($value['script_attributes'])) {
            $this->scriptAttributes = $value['script_attributes'];
            unset($value['script_attributes']);
        }
    
        if (isset($value['link_attributes'])) {
            $this->linkAttributes = $value['link_attributes'];
            unset($value['link_attributes']);
        }
    
        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }
    
    
    public function toArray(): array
    {
        $output = [];
        if (null !== $this->outputPath) {
            $output['output_path'] = $this->outputPath;
        }
        if (null !== $this->crossorigin) {
            $output['crossorigin'] = $this->crossorigin;
        }
        if (null !== $this->preload) {
            $output['preload'] = $this->preload;
        }
        if (null !== $this->cache) {
            $output['cache'] = $this->cache;
        }
        if (null !== $this->strictMode) {
            $output['strict_mode'] = $this->strictMode;
        }
        if (null !== $this->builds) {
            $output['builds'] = $this->builds;
        }
        if (null !== $this->scriptAttributes) {
            $output['script_attributes'] = $this->scriptAttributes;
        }
        if (null !== $this->linkAttributes) {
            $output['link_attributes'] = $this->linkAttributes;
        }
    
        return $output;
    }
    

}
