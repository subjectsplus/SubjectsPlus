<?php

namespace Symfony\Config\Framework;


use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class UidConfig 
{
    private $enabled;
    private $defaultUuidVersion;
    private $nameBasedUuidVersion;
    private $nameBasedUuidNamespace;
    private $timeBasedUuidVersion;
    private $timeBasedUuidNode;
    
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
     * @default 6
     * @param ParamConfigurator|6|4|1 $value
     * @return $this
     */
    public function defaultUuidVersion($value): self
    {
        $this->defaultUuidVersion = $value;
    
        return $this;
    }
    
    /**
     * @default 5
     * @param ParamConfigurator|5|3 $value
     * @return $this
     */
    public function nameBasedUuidVersion($value): self
    {
        $this->nameBasedUuidVersion = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function nameBasedUuidNamespace($value): self
    {
        $this->nameBasedUuidNamespace = $value;
    
        return $this;
    }
    
    /**
     * @default 6
     * @param ParamConfigurator|6|1 $value
     * @return $this
     */
    public function timeBasedUuidVersion($value): self
    {
        $this->timeBasedUuidVersion = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function timeBasedUuidNode($value): self
    {
        $this->timeBasedUuidNode = $value;
    
        return $this;
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['enabled'])) {
            $this->enabled = $value['enabled'];
            unset($value['enabled']);
        }
    
        if (isset($value['default_uuid_version'])) {
            $this->defaultUuidVersion = $value['default_uuid_version'];
            unset($value['default_uuid_version']);
        }
    
        if (isset($value['name_based_uuid_version'])) {
            $this->nameBasedUuidVersion = $value['name_based_uuid_version'];
            unset($value['name_based_uuid_version']);
        }
    
        if (isset($value['name_based_uuid_namespace'])) {
            $this->nameBasedUuidNamespace = $value['name_based_uuid_namespace'];
            unset($value['name_based_uuid_namespace']);
        }
    
        if (isset($value['time_based_uuid_version'])) {
            $this->timeBasedUuidVersion = $value['time_based_uuid_version'];
            unset($value['time_based_uuid_version']);
        }
    
        if (isset($value['time_based_uuid_node'])) {
            $this->timeBasedUuidNode = $value['time_based_uuid_node'];
            unset($value['time_based_uuid_node']);
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
        if (null !== $this->defaultUuidVersion) {
            $output['default_uuid_version'] = $this->defaultUuidVersion;
        }
        if (null !== $this->nameBasedUuidVersion) {
            $output['name_based_uuid_version'] = $this->nameBasedUuidVersion;
        }
        if (null !== $this->nameBasedUuidNamespace) {
            $output['name_based_uuid_namespace'] = $this->nameBasedUuidNamespace;
        }
        if (null !== $this->timeBasedUuidVersion) {
            $output['time_based_uuid_version'] = $this->timeBasedUuidVersion;
        }
        if (null !== $this->timeBasedUuidNode) {
            $output['time_based_uuid_node'] = $this->timeBasedUuidNode;
        }
    
        return $output;
    }
    

}
