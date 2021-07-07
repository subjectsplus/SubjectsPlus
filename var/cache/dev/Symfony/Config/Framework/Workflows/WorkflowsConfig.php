<?php

namespace Symfony\Config\Framework\Workflows;

require_once __DIR__.\DIRECTORY_SEPARATOR.'WorkflowsConfig'.\DIRECTORY_SEPARATOR.'AuditTrailConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'WorkflowsConfig'.\DIRECTORY_SEPARATOR.'MarkingStoreConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'WorkflowsConfig'.\DIRECTORY_SEPARATOR.'PlaceConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'WorkflowsConfig'.\DIRECTORY_SEPARATOR.'TransitionConfig.php';

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Loader\ParamConfigurator;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class WorkflowsConfig 
{
    private $auditTrail;
    private $type;
    private $markingStore;
    private $supports;
    private $supportStrategy;
    private $initialMarking;
    private $eventsToDispatch;
    private $places;
    private $transitions;
    private $metadata;
    
    public function auditTrail(array $value = []): \Symfony\Config\Framework\Workflows\WorkflowsConfig\AuditTrailConfig
    {
        if (null === $this->auditTrail) {
            $this->auditTrail = new \Symfony\Config\Framework\Workflows\WorkflowsConfig\AuditTrailConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "auditTrail()" has already been initialized. You cannot pass values the second time you call auditTrail().');
        }
    
        return $this->auditTrail;
    }
    
    /**
     * @default 'state_machine'
     * @param ParamConfigurator|'workflow'|'state_machine' $value
     * @return $this
     */
    public function type($value): self
    {
        $this->type = $value;
    
        return $this;
    }
    
    public function markingStore(array $value = []): \Symfony\Config\Framework\Workflows\WorkflowsConfig\MarkingStoreConfig
    {
        if (null === $this->markingStore) {
            $this->markingStore = new \Symfony\Config\Framework\Workflows\WorkflowsConfig\MarkingStoreConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "markingStore()" has already been initialized. You cannot pass values the second time you call markingStore().');
        }
    
        return $this->markingStore;
    }
    
    /**
     * @param ParamConfigurator|list<mixed|ParamConfigurator> $value
     * @return $this
     */
    public function supports($value): self
    {
        $this->supports = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function supportStrategy($value): self
    {
        $this->supportStrategy = $value;
    
        return $this;
    }
    
    /**
     * @param ParamConfigurator|list<mixed|ParamConfigurator> $value
     * @return $this
     */
    public function initialMarking($value): self
    {
        $this->initialMarking = $value;
    
        return $this;
    }
    
    /**
     * Select which Transition events should be dispatched for this Workflow
     * @example workflow.enter
     * @example workflow.transition
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function eventsToDispatch($value = NULL): self
    {
        $this->eventsToDispatch = $value;
    
        return $this;
    }
    
    public function place(array $value = []): \Symfony\Config\Framework\Workflows\WorkflowsConfig\PlaceConfig
    {
        return $this->places[] = new \Symfony\Config\Framework\Workflows\WorkflowsConfig\PlaceConfig($value);
    }
    
    public function transition(array $value = []): \Symfony\Config\Framework\Workflows\WorkflowsConfig\TransitionConfig
    {
        return $this->transitions[] = new \Symfony\Config\Framework\Workflows\WorkflowsConfig\TransitionConfig($value);
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
    
        if (isset($value['audit_trail'])) {
            $this->auditTrail = new \Symfony\Config\Framework\Workflows\WorkflowsConfig\AuditTrailConfig($value['audit_trail']);
            unset($value['audit_trail']);
        }
    
        if (isset($value['type'])) {
            $this->type = $value['type'];
            unset($value['type']);
        }
    
        if (isset($value['marking_store'])) {
            $this->markingStore = new \Symfony\Config\Framework\Workflows\WorkflowsConfig\MarkingStoreConfig($value['marking_store']);
            unset($value['marking_store']);
        }
    
        if (isset($value['supports'])) {
            $this->supports = $value['supports'];
            unset($value['supports']);
        }
    
        if (isset($value['support_strategy'])) {
            $this->supportStrategy = $value['support_strategy'];
            unset($value['support_strategy']);
        }
    
        if (isset($value['initial_marking'])) {
            $this->initialMarking = $value['initial_marking'];
            unset($value['initial_marking']);
        }
    
        if (isset($value['events_to_dispatch'])) {
            $this->eventsToDispatch = $value['events_to_dispatch'];
            unset($value['events_to_dispatch']);
        }
    
        if (isset($value['places'])) {
            $this->places = array_map(function ($v) { return new \Symfony\Config\Framework\Workflows\WorkflowsConfig\PlaceConfig($v); }, $value['places']);
            unset($value['places']);
        }
    
        if (isset($value['transitions'])) {
            $this->transitions = array_map(function ($v) { return new \Symfony\Config\Framework\Workflows\WorkflowsConfig\TransitionConfig($v); }, $value['transitions']);
            unset($value['transitions']);
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
        if (null !== $this->auditTrail) {
            $output['audit_trail'] = $this->auditTrail->toArray();
        }
        if (null !== $this->type) {
            $output['type'] = $this->type;
        }
        if (null !== $this->markingStore) {
            $output['marking_store'] = $this->markingStore->toArray();
        }
        if (null !== $this->supports) {
            $output['supports'] = $this->supports;
        }
        if (null !== $this->supportStrategy) {
            $output['support_strategy'] = $this->supportStrategy;
        }
        if (null !== $this->initialMarking) {
            $output['initial_marking'] = $this->initialMarking;
        }
        if (null !== $this->eventsToDispatch) {
            $output['events_to_dispatch'] = $this->eventsToDispatch;
        }
        if (null !== $this->places) {
            $output['places'] = array_map(function ($v) { return $v->toArray(); }, $this->places);
        }
        if (null !== $this->transitions) {
            $output['transitions'] = array_map(function ($v) { return $v->toArray(); }, $this->transitions);
        }
        if (null !== $this->metadata) {
            $output['metadata'] = $this->metadata;
        }
    
        return $output;
    }
    

}
