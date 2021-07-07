<?php

namespace Symfony\Config\Doctrine;

require_once __DIR__.\DIRECTORY_SEPARATOR.'Dbal'.\DIRECTORY_SEPARATOR.'TypeConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'Dbal'.\DIRECTORY_SEPARATOR.'ConnectionConfig.php';

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class DbalConfig 
{
    private $defaultConnection;
    private $types;
    private $connections;
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function defaultConnection($value): self
    {
        $this->defaultConnection = $value;
    
        return $this;
    }
    
    public function type(string $name, array $value = []): \Symfony\Config\Doctrine\Dbal\TypeConfig
    {
        if (!isset($this->types[$name])) {
            return $this->types[$name] = new \Symfony\Config\Doctrine\Dbal\TypeConfig($value);
        }
        if ([] === $value) {
            return $this->types[$name];
        }
    
        throw new InvalidConfigurationException('The node created by "type()" has already been initialized. You cannot pass values the second time you call type().');
    }
    
    public function connection(string $name, array $value = []): \Symfony\Config\Doctrine\Dbal\ConnectionConfig
    {
        if (!isset($this->connections[$name])) {
            return $this->connections[$name] = new \Symfony\Config\Doctrine\Dbal\ConnectionConfig($value);
        }
        if ([] === $value) {
            return $this->connections[$name];
        }
    
        throw new InvalidConfigurationException('The node created by "connection()" has already been initialized. You cannot pass values the second time you call connection().');
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['default_connection'])) {
            $this->defaultConnection = $value['default_connection'];
            unset($value['default_connection']);
        }
    
        if (isset($value['types'])) {
            $this->types = array_map(function ($v) { return new \Symfony\Config\Doctrine\Dbal\TypeConfig($v); }, $value['types']);
            unset($value['types']);
        }
    
        if (isset($value['connections'])) {
            $this->connections = array_map(function ($v) { return new \Symfony\Config\Doctrine\Dbal\ConnectionConfig($v); }, $value['connections']);
            unset($value['connections']);
        }
    
        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }
    
    
    public function toArray(): array
    {
        $output = [];
        if (null !== $this->defaultConnection) {
            $output['default_connection'] = $this->defaultConnection;
        }
        if (null !== $this->types) {
            $output['types'] = array_map(function ($v) { return $v->toArray(); }, $this->types);
        }
        if (null !== $this->connections) {
            $output['connections'] = array_map(function ($v) { return $v->toArray(); }, $this->connections);
        }
    
        return $output;
    }
    

}
