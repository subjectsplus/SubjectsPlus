<?php

namespace Symfony\Config\Framework;

require_once __DIR__.\DIRECTORY_SEPARATOR.'Workflows'.\DIRECTORY_SEPARATOR.'WorkflowsConfig.php';

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class WorkflowsConfig 
{
    private $enabled;
    private $workflows;
    
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
    
    public function workflows(string $name, array $value = []): \Symfony\Config\Framework\Workflows\WorkflowsConfig
    {
        if (!isset($this->workflows[$name])) {
            return $this->workflows[$name] = new \Symfony\Config\Framework\Workflows\WorkflowsConfig($value);
        }
        if ([] === $value) {
            return $this->workflows[$name];
        }
    
        throw new InvalidConfigurationException('The node created by "workflows()" has already been initialized. You cannot pass values the second time you call workflows().');
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['enabled'])) {
            $this->enabled = $value['enabled'];
            unset($value['enabled']);
        }
    
        if (isset($value['workflows'])) {
            $this->workflows = array_map(function ($v) { return new \Symfony\Config\Framework\Workflows\WorkflowsConfig($v); }, $value['workflows']);
            unset($value['workflows']);
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
        if (null !== $this->workflows) {
            $output['workflows'] = array_map(function ($v) { return $v->toArray(); }, $this->workflows);
        }
    
        return $output;
    }
    

}
