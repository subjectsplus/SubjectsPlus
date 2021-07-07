<?php

namespace Symfony\Config\Doctrine\Dbal;


use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class TypeConfig 
{
    private $class;
    private $commented;
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function class($value): self
    {
        $this->class = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|bool $value
     * @deprecated The doctrine-bundle type commenting features were removed; the corresponding config parameter was deprecated in 2.0 and will be dropped in 3.0.
     * @return $this
     */
    public function commented($value): self
    {
        $this->commented = $value;
    
        return $this;
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['class'])) {
            $this->class = $value['class'];
            unset($value['class']);
        }
    
        if (isset($value['commented'])) {
            $this->commented = $value['commented'];
            unset($value['commented']);
        }
    
        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }
    
    
    public function toArray(): array
    {
        $output = [];
        if (null !== $this->class) {
            $output['class'] = $this->class;
        }
        if (null !== $this->commented) {
            $output['commented'] = $this->commented;
        }
    
        return $output;
    }
    

}
