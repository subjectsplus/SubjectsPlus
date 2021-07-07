<?php

namespace Symfony\Config\Framework;

require_once __DIR__.\DIRECTORY_SEPARATOR.'Form'.\DIRECTORY_SEPARATOR.'CsrfProtectionConfig.php';

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class FormConfig 
{
    private $enabled;
    private $csrfProtection;
    private $legacyErrorMessages;
    
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
    
    public function csrfProtection(array $value = []): \Symfony\Config\Framework\Form\CsrfProtectionConfig
    {
        if (null === $this->csrfProtection) {
            $this->csrfProtection = new \Symfony\Config\Framework\Form\CsrfProtectionConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "csrfProtection()" has already been initialized. You cannot pass values the second time you call csrfProtection().');
        }
    
        return $this->csrfProtection;
    }
    
    /**
     * @default true
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function legacyErrorMessages($value): self
    {
        $this->legacyErrorMessages = $value;
    
        return $this;
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['enabled'])) {
            $this->enabled = $value['enabled'];
            unset($value['enabled']);
        }
    
        if (isset($value['csrf_protection'])) {
            $this->csrfProtection = new \Symfony\Config\Framework\Form\CsrfProtectionConfig($value['csrf_protection']);
            unset($value['csrf_protection']);
        }
    
        if (isset($value['legacy_error_messages'])) {
            $this->legacyErrorMessages = $value['legacy_error_messages'];
            unset($value['legacy_error_messages']);
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
        if (null !== $this->csrfProtection) {
            $output['csrf_protection'] = $this->csrfProtection->toArray();
        }
        if (null !== $this->legacyErrorMessages) {
            $output['legacy_error_messages'] = $this->legacyErrorMessages;
        }
    
        return $output;
    }
    

}
