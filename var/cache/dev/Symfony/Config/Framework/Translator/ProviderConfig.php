<?php

namespace Symfony\Config\Framework\Translator;


use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class ProviderConfig 
{
    private $dsn;
    private $domains;
    private $locales;
    
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
     * @param ParamConfigurator|list<mixed|ParamConfigurator> $value
     * @return $this
     */
    public function domains($value): self
    {
        $this->domains = $value;
    
        return $this;
    }
    
    /**
     * @param ParamConfigurator|list<mixed|ParamConfigurator> $value
     * @return $this
     */
    public function locales($value): self
    {
        $this->locales = $value;
    
        return $this;
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['dsn'])) {
            $this->dsn = $value['dsn'];
            unset($value['dsn']);
        }
    
        if (isset($value['domains'])) {
            $this->domains = $value['domains'];
            unset($value['domains']);
        }
    
        if (isset($value['locales'])) {
            $this->locales = $value['locales'];
            unset($value['locales']);
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
        if (null !== $this->domains) {
            $output['domains'] = $this->domains;
        }
        if (null !== $this->locales) {
            $output['locales'] = $this->locales;
        }
    
        return $output;
    }
    

}
