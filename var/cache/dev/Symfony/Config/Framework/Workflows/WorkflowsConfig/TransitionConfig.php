<?php

namespace Symfony\Config\Framework\Workflows\WorkflowsConfig;


use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class TransitionConfig 
{
    private $name;
    private $guard;
    private $from;
    private $to;
    private $metadata;
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function name($value): self
    {
        $this->name = $value;
    
        return $this;
    }
    
    /**
     * An expression to block the transition
     * @example is_fully_authenticated() and is_granted('ROLE_JOURNALIST') and subject.getTitle() == 'My first article'
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function guard($value): self
    {
        $this->guard = $value;
    
        return $this;
    }
    
    /**
     * @param ParamConfigurator|list<mixed|ParamConfigurator> $value
     * @return $this
     */
    public function from($value): self
    {
        $this->from = $value;
    
        return $this;
    }
    
    /**
     * @param ParamConfigurator|list<mixed|ParamConfigurator> $value
     * @return $this
     */
    public function to($value): self
    {
        $this->to = $value;
    
        return $this;
    }
    
    /**
     * @param ParamConfigurator|list<mixed|ParamConfigurator> $value
     * @return $this
     */
    public function metadata($value): self
    {
        $this->metadata = $value;
    
        return $this;
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['name'])) {
            $this->name = $value['name'];
            unset($value['name']);
        }
    
        if (isset($value['guard'])) {
            $this->guard = $value['guard'];
            unset($value['guard']);
        }
    
        if (isset($value['from'])) {
            $this->from = $value['from'];
            unset($value['from']);
        }
    
        if (isset($value['to'])) {
            $this->to = $value['to'];
            unset($value['to']);
        }
    
        if (isset($value['metadata'])) {
            $this->metadata = $value['metadata'];
            unset($value['metadata']);
        }
    
        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }
    
    
    public function toArray(): array
    {
        $output = [];
        if (null !== $this->name) {
            $output['name'] = $this->name;
        }
        if (null !== $this->guard) {
            $output['guard'] = $this->guard;
        }
        if (null !== $this->from) {
            $output['from'] = $this->from;
        }
        if (null !== $this->to) {
            $output['to'] = $this->to;
        }
        if (null !== $this->metadata) {
            $output['metadata'] = $this->metadata;
        }
    
        return $output;
    }
    

}
