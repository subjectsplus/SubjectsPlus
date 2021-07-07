<?php

namespace Symfony\Config\Framework;

require_once __DIR__.\DIRECTORY_SEPARATOR.'Messenger'.\DIRECTORY_SEPARATOR.'RoutingConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'Messenger'.\DIRECTORY_SEPARATOR.'SerializerConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'Messenger'.\DIRECTORY_SEPARATOR.'TransportConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'Messenger'.\DIRECTORY_SEPARATOR.'BusConfig.php';

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class MessengerConfig 
{
    private $enabled;
    private $routing;
    private $serializer;
    private $transports;
    private $failureTransport;
    private $defaultBus;
    private $buses;
    
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
    
    public function routing(string $message_class, array $value = []): \Symfony\Config\Framework\Messenger\RoutingConfig
    {
        if (!isset($this->routing[$message_class])) {
            return $this->routing[$message_class] = new \Symfony\Config\Framework\Messenger\RoutingConfig($value);
        }
        if ([] === $value) {
            return $this->routing[$message_class];
        }
    
        throw new InvalidConfigurationException('The node created by "routing()" has already been initialized. You cannot pass values the second time you call routing().');
    }
    
    public function serializer(array $value = []): \Symfony\Config\Framework\Messenger\SerializerConfig
    {
        if (null === $this->serializer) {
            $this->serializer = new \Symfony\Config\Framework\Messenger\SerializerConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "serializer()" has already been initialized. You cannot pass values the second time you call serializer().');
        }
    
        return $this->serializer;
    }
    
    public function transport(string $name, array $value = []): \Symfony\Config\Framework\Messenger\TransportConfig
    {
        if (!isset($this->transports[$name])) {
            return $this->transports[$name] = new \Symfony\Config\Framework\Messenger\TransportConfig($value);
        }
        if ([] === $value) {
            return $this->transports[$name];
        }
    
        throw new InvalidConfigurationException('The node created by "transport()" has already been initialized. You cannot pass values the second time you call transport().');
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
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function defaultBus($value): self
    {
        $this->defaultBus = $value;
    
        return $this;
    }
    
    public function bus(string $name, array $value = []): \Symfony\Config\Framework\Messenger\BusConfig
    {
        if (!isset($this->buses[$name])) {
            return $this->buses[$name] = new \Symfony\Config\Framework\Messenger\BusConfig($value);
        }
        if ([] === $value) {
            return $this->buses[$name];
        }
    
        throw new InvalidConfigurationException('The node created by "bus()" has already been initialized. You cannot pass values the second time you call bus().');
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['enabled'])) {
            $this->enabled = $value['enabled'];
            unset($value['enabled']);
        }
    
        if (isset($value['routing'])) {
            $this->routing = array_map(function ($v) { return new \Symfony\Config\Framework\Messenger\RoutingConfig($v); }, $value['routing']);
            unset($value['routing']);
        }
    
        if (isset($value['serializer'])) {
            $this->serializer = new \Symfony\Config\Framework\Messenger\SerializerConfig($value['serializer']);
            unset($value['serializer']);
        }
    
        if (isset($value['transports'])) {
            $this->transports = array_map(function ($v) { return new \Symfony\Config\Framework\Messenger\TransportConfig($v); }, $value['transports']);
            unset($value['transports']);
        }
    
        if (isset($value['failure_transport'])) {
            $this->failureTransport = $value['failure_transport'];
            unset($value['failure_transport']);
        }
    
        if (isset($value['default_bus'])) {
            $this->defaultBus = $value['default_bus'];
            unset($value['default_bus']);
        }
    
        if (isset($value['buses'])) {
            $this->buses = array_map(function ($v) { return new \Symfony\Config\Framework\Messenger\BusConfig($v); }, $value['buses']);
            unset($value['buses']);
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
        if (null !== $this->routing) {
            $output['routing'] = array_map(function ($v) { return $v->toArray(); }, $this->routing);
        }
        if (null !== $this->serializer) {
            $output['serializer'] = $this->serializer->toArray();
        }
        if (null !== $this->transports) {
            $output['transports'] = array_map(function ($v) { return $v->toArray(); }, $this->transports);
        }
        if (null !== $this->failureTransport) {
            $output['failure_transport'] = $this->failureTransport;
        }
        if (null !== $this->defaultBus) {
            $output['default_bus'] = $this->defaultBus;
        }
        if (null !== $this->buses) {
            $output['buses'] = array_map(function ($v) { return $v->toArray(); }, $this->buses);
        }
    
        return $output;
    }
    

}
