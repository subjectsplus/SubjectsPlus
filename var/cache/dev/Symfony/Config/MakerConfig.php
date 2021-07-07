<?php

namespace Symfony\Config;


use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class MakerConfig implements \Symfony\Component\Config\Builder\ConfigBuilderInterface
{
    private $rootNamespace;
    
    /**
     * @default 'App'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function rootNamespace($value): self
    {
        $this->rootNamespace = $value;
    
        return $this;
    }
    
    public function getExtensionAlias(): string
    {
        return 'maker';
    }
            
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['root_namespace'])) {
            $this->rootNamespace = $value['root_namespace'];
            unset($value['root_namespace']);
        }
    
        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }
    
    
    public function toArray(): array
    {
        $output = [];
        if (null !== $this->rootNamespace) {
            $output['root_namespace'] = $this->rootNamespace;
        }
    
        return $output;
    }
    

}
