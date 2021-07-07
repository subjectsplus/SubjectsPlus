<?php

namespace Symfony\Config\Framework;

require_once __DIR__.\DIRECTORY_SEPARATOR.'Serializer'.\DIRECTORY_SEPARATOR.'MappingConfig.php';

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class SerializerConfig 
{
    private $enabled;
    private $enableAnnotations;
    private $nameConverter;
    private $circularReferenceHandler;
    private $maxDepthHandler;
    private $mapping;
    
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
     * @default true
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function enableAnnotations($value): self
    {
        $this->enableAnnotations = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function nameConverter($value): self
    {
        $this->nameConverter = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function circularReferenceHandler($value): self
    {
        $this->circularReferenceHandler = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function maxDepthHandler($value): self
    {
        $this->maxDepthHandler = $value;
    
        return $this;
    }
    
    public function mapping(array $value = []): \Symfony\Config\Framework\Serializer\MappingConfig
    {
        if (null === $this->mapping) {
            $this->mapping = new \Symfony\Config\Framework\Serializer\MappingConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "mapping()" has already been initialized. You cannot pass values the second time you call mapping().');
        }
    
        return $this->mapping;
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['enabled'])) {
            $this->enabled = $value['enabled'];
            unset($value['enabled']);
        }
    
        if (isset($value['enable_annotations'])) {
            $this->enableAnnotations = $value['enable_annotations'];
            unset($value['enable_annotations']);
        }
    
        if (isset($value['name_converter'])) {
            $this->nameConverter = $value['name_converter'];
            unset($value['name_converter']);
        }
    
        if (isset($value['circular_reference_handler'])) {
            $this->circularReferenceHandler = $value['circular_reference_handler'];
            unset($value['circular_reference_handler']);
        }
    
        if (isset($value['max_depth_handler'])) {
            $this->maxDepthHandler = $value['max_depth_handler'];
            unset($value['max_depth_handler']);
        }
    
        if (isset($value['mapping'])) {
            $this->mapping = new \Symfony\Config\Framework\Serializer\MappingConfig($value['mapping']);
            unset($value['mapping']);
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
        if (null !== $this->enableAnnotations) {
            $output['enable_annotations'] = $this->enableAnnotations;
        }
        if (null !== $this->nameConverter) {
            $output['name_converter'] = $this->nameConverter;
        }
        if (null !== $this->circularReferenceHandler) {
            $output['circular_reference_handler'] = $this->circularReferenceHandler;
        }
        if (null !== $this->maxDepthHandler) {
            $output['max_depth_handler'] = $this->maxDepthHandler;
        }
        if (null !== $this->mapping) {
            $output['mapping'] = $this->mapping->toArray();
        }
    
        return $output;
    }
    

}
