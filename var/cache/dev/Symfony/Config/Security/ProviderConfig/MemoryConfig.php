<?php

namespace Symfony\Config\Security\ProviderConfig;

require_once __DIR__.\DIRECTORY_SEPARATOR.'Memory'.\DIRECTORY_SEPARATOR.'UserConfig.php';

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class MemoryConfig 
{
    private $users;
    
    public function user(string $identifier, array $value = []): \Symfony\Config\Security\ProviderConfig\Memory\UserConfig
    {
        if (!isset($this->users[$identifier])) {
            return $this->users[$identifier] = new \Symfony\Config\Security\ProviderConfig\Memory\UserConfig($value);
        }
        if ([] === $value) {
            return $this->users[$identifier];
        }
    
        throw new InvalidConfigurationException('The node created by "user()" has already been initialized. You cannot pass values the second time you call user().');
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['users'])) {
            $this->users = array_map(function ($v) { return new \Symfony\Config\Security\ProviderConfig\Memory\UserConfig($v); }, $value['users']);
            unset($value['users']);
        }
    
        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }
    
    
    public function toArray(): array
    {
        $output = [];
        if (null !== $this->users) {
            $output['users'] = array_map(function ($v) { return $v->toArray(); }, $this->users);
        }
    
        return $output;
    }
    

}
