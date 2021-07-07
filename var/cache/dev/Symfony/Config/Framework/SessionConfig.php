<?php

namespace Symfony\Config\Framework;


use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class SessionConfig 
{
    private $enabled;
    private $storageId;
    private $storageFactoryId;
    private $handlerId;
    private $name;
    private $cookieLifetime;
    private $cookiePath;
    private $cookieDomain;
    private $cookieSecure;
    private $cookieHttponly;
    private $cookieSamesite;
    private $useCookies;
    private $gcDivisor;
    private $gcProbability;
    private $gcMaxlifetime;
    private $savePath;
    private $metadataUpdateThreshold;
    private $sidLength;
    private $sidBitsPerCharacter;
    
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
    
    /**
     * @default 'session.storage.native'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function storageId($value): self
    {
        $this->storageId = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function storageFactoryId($value): self
    {
        $this->storageFactoryId = $value;
    
        return $this;
    }
    
    /**
     * @default 'session.handler.native_file'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function handlerId($value): self
    {
        $this->handlerId = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function name($value): self
    {
        $this->name = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function cookieLifetime($value): self
    {
        $this->cookieLifetime = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function cookiePath($value): self
    {
        $this->cookiePath = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function cookieDomain($value): self
    {
        $this->cookieDomain = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|true|false|'auto' $value
     * @return $this
     */
    public function cookieSecure($value): self
    {
        $this->cookieSecure = $value;
    
        return $this;
    }
    
    /**
     * @default true
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function cookieHttponly($value): self
    {
        $this->cookieHttponly = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|NULL|'lax'|'strict'|'none' $value
     * @return $this
     */
    public function cookieSamesite($value): self
    {
        $this->cookieSamesite = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function useCookies($value): self
    {
        $this->useCookies = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function gcDivisor($value): self
    {
        $this->gcDivisor = $value;
    
        return $this;
    }
    
    /**
     * @default 1
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function gcProbability($value): self
    {
        $this->gcProbability = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function gcMaxlifetime($value): self
    {
        $this->gcMaxlifetime = $value;
    
        return $this;
    }
    
    /**
     * @default '%kernel.cache_dir%/sessions'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function savePath($value): self
    {
        $this->savePath = $value;
    
        return $this;
    }
    
    /**
     * seconds to wait between 2 session metadata updates
     * @default 0
     * @param ParamConfigurator|int $value
     * @return $this
     */
    public function metadataUpdateThreshold($value): self
    {
        $this->metadataUpdateThreshold = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|int $value
     * @return $this
     */
    public function sidLength($value): self
    {
        $this->sidLength = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|int $value
     * @return $this
     */
    public function sidBitsPerCharacter($value): self
    {
        $this->sidBitsPerCharacter = $value;
    
        return $this;
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['enabled'])) {
            $this->enabled = $value['enabled'];
            unset($value['enabled']);
        }
    
        if (isset($value['storage_id'])) {
            $this->storageId = $value['storage_id'];
            unset($value['storage_id']);
        }
    
        if (isset($value['storage_factory_id'])) {
            $this->storageFactoryId = $value['storage_factory_id'];
            unset($value['storage_factory_id']);
        }
    
        if (isset($value['handler_id'])) {
            $this->handlerId = $value['handler_id'];
            unset($value['handler_id']);
        }
    
        if (isset($value['name'])) {
            $this->name = $value['name'];
            unset($value['name']);
        }
    
        if (isset($value['cookie_lifetime'])) {
            $this->cookieLifetime = $value['cookie_lifetime'];
            unset($value['cookie_lifetime']);
        }
    
        if (isset($value['cookie_path'])) {
            $this->cookiePath = $value['cookie_path'];
            unset($value['cookie_path']);
        }
    
        if (isset($value['cookie_domain'])) {
            $this->cookieDomain = $value['cookie_domain'];
            unset($value['cookie_domain']);
        }
    
        if (isset($value['cookie_secure'])) {
            $this->cookieSecure = $value['cookie_secure'];
            unset($value['cookie_secure']);
        }
    
        if (isset($value['cookie_httponly'])) {
            $this->cookieHttponly = $value['cookie_httponly'];
            unset($value['cookie_httponly']);
        }
    
        if (isset($value['cookie_samesite'])) {
            $this->cookieSamesite = $value['cookie_samesite'];
            unset($value['cookie_samesite']);
        }
    
        if (isset($value['use_cookies'])) {
            $this->useCookies = $value['use_cookies'];
            unset($value['use_cookies']);
        }
    
        if (isset($value['gc_divisor'])) {
            $this->gcDivisor = $value['gc_divisor'];
            unset($value['gc_divisor']);
        }
    
        if (isset($value['gc_probability'])) {
            $this->gcProbability = $value['gc_probability'];
            unset($value['gc_probability']);
        }
    
        if (isset($value['gc_maxlifetime'])) {
            $this->gcMaxlifetime = $value['gc_maxlifetime'];
            unset($value['gc_maxlifetime']);
        }
    
        if (isset($value['save_path'])) {
            $this->savePath = $value['save_path'];
            unset($value['save_path']);
        }
    
        if (isset($value['metadata_update_threshold'])) {
            $this->metadataUpdateThreshold = $value['metadata_update_threshold'];
            unset($value['metadata_update_threshold']);
        }
    
        if (isset($value['sid_length'])) {
            $this->sidLength = $value['sid_length'];
            unset($value['sid_length']);
        }
    
        if (isset($value['sid_bits_per_character'])) {
            $this->sidBitsPerCharacter = $value['sid_bits_per_character'];
            unset($value['sid_bits_per_character']);
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
        if (null !== $this->storageId) {
            $output['storage_id'] = $this->storageId;
        }
        if (null !== $this->storageFactoryId) {
            $output['storage_factory_id'] = $this->storageFactoryId;
        }
        if (null !== $this->handlerId) {
            $output['handler_id'] = $this->handlerId;
        }
        if (null !== $this->name) {
            $output['name'] = $this->name;
        }
        if (null !== $this->cookieLifetime) {
            $output['cookie_lifetime'] = $this->cookieLifetime;
        }
        if (null !== $this->cookiePath) {
            $output['cookie_path'] = $this->cookiePath;
        }
        if (null !== $this->cookieDomain) {
            $output['cookie_domain'] = $this->cookieDomain;
        }
        if (null !== $this->cookieSecure) {
            $output['cookie_secure'] = $this->cookieSecure;
        }
        if (null !== $this->cookieHttponly) {
            $output['cookie_httponly'] = $this->cookieHttponly;
        }
        if (null !== $this->cookieSamesite) {
            $output['cookie_samesite'] = $this->cookieSamesite;
        }
        if (null !== $this->useCookies) {
            $output['use_cookies'] = $this->useCookies;
        }
        if (null !== $this->gcDivisor) {
            $output['gc_divisor'] = $this->gcDivisor;
        }
        if (null !== $this->gcProbability) {
            $output['gc_probability'] = $this->gcProbability;
        }
        if (null !== $this->gcMaxlifetime) {
            $output['gc_maxlifetime'] = $this->gcMaxlifetime;
        }
        if (null !== $this->savePath) {
            $output['save_path'] = $this->savePath;
        }
        if (null !== $this->metadataUpdateThreshold) {
            $output['metadata_update_threshold'] = $this->metadataUpdateThreshold;
        }
        if (null !== $this->sidLength) {
            $output['sid_length'] = $this->sidLength;
        }
        if (null !== $this->sidBitsPerCharacter) {
            $output['sid_bits_per_character'] = $this->sidBitsPerCharacter;
        }
    
        return $output;
    }
    

}
