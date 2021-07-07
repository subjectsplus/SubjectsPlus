<?php

namespace Symfony\Config\Framework\Messenger;

require_once __DIR__.\DIRECTORY_SEPARATOR.'TransportConfig'.\DIRECTORY_SEPARATOR.'RetryStrategyConfig.php';

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class TransportConfig 
{
    private $dsn;
    private $serializer;
    private $options;
    private $failureTransport;
    private $retryStrategy;
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function dsn($value): self
    {
        $this->dsn = $value;
    
        return $this;
    }
    
    /**
     * Service id of a custom serializer to use.
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function serializer($value): self
    {
        $this->serializer = $value;
    
        return $this;
    }
    
    /**
     * @param ParamConfigurator|list<mixed|ParamConfigurator> $value
     * @return $this
     */
    public function options($value): self
    {
        $this->options = $value;
    
        return $this;
    }
    
    /**
     * Transport name to send failed messages to (after all retries have failed).
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function failureTransport($value): self
    {
        $this->failureTransport = $value;
    
        return $this;
    }
    
    public function retryStrategy(array $value = []): \Symfony\Config\Framework\Messenger\TransportConfig\RetryStrategyConfig
    {
        if (null === $this->retryStrategy) {
            $this->retryStrategy = new \Symfony\Config\Framework\Messenger\TransportConfig\RetryStrategyConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "retryStrategy()" has already been initialized. You cannot pass values the second time you call retryStrategy().');
        }
    
        return $this->retryStrategy;
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['dsn'])) {
            $this->dsn = $value['dsn'];
            unset($value['dsn']);
        }
    
        if (isset($value['serializer'])) {
            $this->serializer = $value['serializer'];
            unset($value['serializer']);
        }
    
        if (isset($value['options'])) {
            $this->options = $value['options'];
            unset($value['options']);
        }
    
        if (isset($value['failure_transport'])) {
            $this->failureTransport = $value['failure_transport'];
            unset($value['failure_transport']);
        }
    
        if (isset($value['retry_strategy'])) {
            $this->retryStrategy = new \Symfony\Config\Framework\Messenger\TransportConfig\RetryStrategyConfig($value['retry_strategy']);
            unset($value['retry_strategy']);
        }
    
        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }
    
    
    public function toArray(): array
    {
        $output = [];
        if (null !== $this->dsn) {
            $output['dsn'] = $this->dsn;
        }
        if (null !== $this->serializer) {
            $output['serializer'] = $this->serializer;
        }
        if (null !== $this->options) {
            $output['options'] = $this->options;
        }
        if (null !== $this->failureTransport) {
            $output['failure_transport'] = $this->failureTransport;
        }
        if (null !== $this->retryStrategy) {
            $output['retry_strategy'] = $this->retryStrategy->toArray();
        }
    
        return $output;
    }
    

}
