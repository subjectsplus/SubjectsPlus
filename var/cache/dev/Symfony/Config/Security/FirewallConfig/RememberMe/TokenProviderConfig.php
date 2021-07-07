<?php

namespace Symfony\Config\Security\FirewallConfig\RememberMe;

require_once __DIR__.\DIRECTORY_SEPARATOR.'TokenProvider'.\DIRECTORY_SEPARATOR.'DoctrineConfig.php';

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class TokenProviderConfig 
{
    private $service;
    private $doctrine;
    
    /**
     * The service ID of a custom rememberme token provider.
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function service($value): self
    {
        $this->service = $value;
    
        return $this;
    }
    
    public function doctrine(array $value = []): \Symfony\Config\Security\FirewallConfig\RememberMe\TokenProvider\DoctrineConfig
    {
        if (null === $this->doctrine) {
            $this->doctrine = new \Symfony\Config\Security\FirewallConfig\RememberMe\TokenProvider\DoctrineConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "doctrine()" has already been initialized. You cannot pass values the second time you call doctrine().');
        }
    
        return $this->doctrine;
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['service'])) {
            $this->service = $value['service'];
            unset($value['service']);
        }
    
        if (isset($value['doctrine'])) {
            $this->doctrine = new \Symfony\Config\Security\FirewallConfig\RememberMe\TokenProvider\DoctrineConfig($value['doctrine']);
            unset($value['doctrine']);
        }
    
        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }
    
    
    public function toArray(): array
    {
        $output = [];
        if (null !== $this->service) {
            $output['service'] = $this->service;
        }
        if (null !== $this->doctrine) {
            $output['doctrine'] = $this->doctrine->toArray();
        }
    
        return $output;
    }
    

}
