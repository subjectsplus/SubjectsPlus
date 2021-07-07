<?php

namespace Symfony\Config;


use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class DebugConfig implements \Symfony\Component\Config\Builder\ConfigBuilderInterface
{
    private $maxItems;
    private $minDepth;
    private $maxStringLength;
    private $dumpDestination;
    private $theme;
    
    /**
     * Max number of displayed items past the first level, -1 means no limit
     * @default 2500
     * @param ParamConfigurator|int $value
     * @return $this
     */
    public function maxItems($value): self
    {
        $this->maxItems = $value;
    
        return $this;
    }
    
    /**
     * Minimum tree depth to clone all the items, 1 is default
     * @default 1
     * @param ParamConfigurator|int $value
     * @return $this
     */
    public function minDepth($value): self
    {
        $this->minDepth = $value;
    
        return $this;
    }
    
    /**
     * Max length of displayed strings, -1 means no limit
     * @default -1
     * @param ParamConfigurator|int $value
     * @return $this
     */
    public function maxStringLength($value): self
    {
        $this->maxStringLength = $value;
    
        return $this;
    }
    
    /**
     * A stream URL where dumps should be written to
     * @example php://stderr, or tcp://%env(VAR_DUMPER_SERVER)% when using the "server:dump" command
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function dumpDestination($value): self
    {
        $this->dumpDestination = $value;
    
        return $this;
    }
    
    /**
     * Changes the color of the dump() output when rendered directly on the templating. "dark" (default) or "light"
     * @example dark
     * @default 'dark'
     * @param ParamConfigurator|'dark'|'light' $value
     * @return $this
     */
    public function theme($value): self
    {
        $this->theme = $value;
    
        return $this;
    }
    
    public function getExtensionAlias(): string
    {
        return 'debug';
    }
            
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['max_items'])) {
            $this->maxItems = $value['max_items'];
            unset($value['max_items']);
        }
    
        if (isset($value['min_depth'])) {
            $this->minDepth = $value['min_depth'];
            unset($value['min_depth']);
        }
    
        if (isset($value['max_string_length'])) {
            $this->maxStringLength = $value['max_string_length'];
            unset($value['max_string_length']);
        }
    
        if (isset($value['dump_destination'])) {
            $this->dumpDestination = $value['dump_destination'];
            unset($value['dump_destination']);
        }
    
        if (isset($value['theme'])) {
            $this->theme = $value['theme'];
            unset($value['theme']);
        }
    
        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }
    
    
    public function toArray(): array
    {
        $output = [];
        if (null !== $this->maxItems) {
            $output['max_items'] = $this->maxItems;
        }
        if (null !== $this->minDepth) {
            $output['min_depth'] = $this->minDepth;
        }
        if (null !== $this->maxStringLength) {
            $output['max_string_length'] = $this->maxStringLength;
        }
        if (null !== $this->dumpDestination) {
            $output['dump_destination'] = $this->dumpDestination;
        }
        if (null !== $this->theme) {
            $output['theme'] = $this->theme;
        }
    
        return $output;
    }
    

}
