<?php

namespace Symfony\Config\Framework\Assets;


use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class PackageConfig 
{
    private $versionStrategy;
    private $version;
    private $versionFormat;
    private $jsonManifestPath;
    private $basePath;
    private $baseUrls;
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function versionStrategy($value): self
    {
        $this->versionStrategy = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function version($value): self
    {
        $this->version = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function versionFormat($value): self
    {
        $this->versionFormat = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function jsonManifestPath($value): self
    {
        $this->jsonManifestPath = $value;
    
        return $this;
    }
    
    /**
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function basePath($value): self
    {
        $this->basePath = $value;
    
        return $this;
    }
    
    /**
     * @param ParamConfigurator|list<mixed|ParamConfigurator> $value
     * @return $this
     */
    public function baseUrls($value): self
    {
        $this->baseUrls = $value;
    
        return $this;
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['version_strategy'])) {
            $this->versionStrategy = $value['version_strategy'];
            unset($value['version_strategy']);
        }
    
        if (isset($value['version'])) {
            $this->version = $value['version'];
            unset($value['version']);
        }
    
        if (isset($value['version_format'])) {
            $this->versionFormat = $value['version_format'];
            unset($value['version_format']);
        }
    
        if (isset($value['json_manifest_path'])) {
            $this->jsonManifestPath = $value['json_manifest_path'];
            unset($value['json_manifest_path']);
        }
    
        if (isset($value['base_path'])) {
            $this->basePath = $value['base_path'];
            unset($value['base_path']);
        }
    
        if (isset($value['base_urls'])) {
            $this->baseUrls = $value['base_urls'];
            unset($value['base_urls']);
        }
    
        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }
    
    
    public function toArray(): array
    {
        $output = [];
        if (null !== $this->versionStrategy) {
            $output['version_strategy'] = $this->versionStrategy;
        }
        if (null !== $this->version) {
            $output['version'] = $this->version;
        }
        if (null !== $this->versionFormat) {
            $output['version_format'] = $this->versionFormat;
        }
        if (null !== $this->jsonManifestPath) {
            $output['json_manifest_path'] = $this->jsonManifestPath;
        }
        if (null !== $this->basePath) {
            $output['base_path'] = $this->basePath;
        }
        if (null !== $this->baseUrls) {
            $output['base_urls'] = $this->baseUrls;
        }
    
        return $output;
    }
    

}
