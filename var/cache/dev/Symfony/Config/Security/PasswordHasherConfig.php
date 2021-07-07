<?php

namespace Symfony\Config\Security;


use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class PasswordHasherConfig 
{
    private $algorithm;
    private $migrateFrom;
    private $hashAlgorithm;
    private $keyLength;
    private $ignoreCase;
    private $encodeAsBase64;
    private $iterations;
    private $cost;
    private $memoryCost;
    private $timeCost;
    private $id;
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function algorithm($value): self
    {
        $this->algorithm = $value;
    
        return $this;
    }
    
    /**
     * @param ParamConfigurator|list<mixed|ParamConfigurator> $value
     * @return $this
     */
    public function migrateFrom($value): self
    {
        $this->migrateFrom = $value;
    
        return $this;
    }
    
    /**
     * Name of hashing algorithm for PBKDF2 (i.e. sha256, sha512, etc..) See hash_algos() for a list of supported algorithms.
     * @default 'sha512'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function hashAlgorithm($value): self
    {
        $this->hashAlgorithm = $value;
    
        return $this;
    }
    
    /**
     * @default 40
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function keyLength($value): self
    {
        $this->keyLength = $value;
    
        return $this;
    }
    
    /**
     * @default false
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function ignoreCase($value): self
    {
        $this->ignoreCase = $value;
    
        return $this;
    }
    
    /**
     * @default true
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function encodeAsBase64($value): self
    {
        $this->encodeAsBase64 = $value;
    
        return $this;
    }
    
    /**
     * @default 5000
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function iterations($value): self
    {
        $this->iterations = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|int $value
     * @return $this
     */
    public function cost($value): self
    {
        $this->cost = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function memoryCost($value): self
    {
        $this->memoryCost = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function timeCost($value): self
    {
        $this->timeCost = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function id($value): self
    {
        $this->id = $value;
    
        return $this;
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['algorithm'])) {
            $this->algorithm = $value['algorithm'];
            unset($value['algorithm']);
        }
    
        if (isset($value['migrate_from'])) {
            $this->migrateFrom = $value['migrate_from'];
            unset($value['migrate_from']);
        }
    
        if (isset($value['hash_algorithm'])) {
            $this->hashAlgorithm = $value['hash_algorithm'];
            unset($value['hash_algorithm']);
        }
    
        if (isset($value['key_length'])) {
            $this->keyLength = $value['key_length'];
            unset($value['key_length']);
        }
    
        if (isset($value['ignore_case'])) {
            $this->ignoreCase = $value['ignore_case'];
            unset($value['ignore_case']);
        }
    
        if (isset($value['encode_as_base64'])) {
            $this->encodeAsBase64 = $value['encode_as_base64'];
            unset($value['encode_as_base64']);
        }
    
        if (isset($value['iterations'])) {
            $this->iterations = $value['iterations'];
            unset($value['iterations']);
        }
    
        if (isset($value['cost'])) {
            $this->cost = $value['cost'];
            unset($value['cost']);
        }
    
        if (isset($value['memory_cost'])) {
            $this->memoryCost = $value['memory_cost'];
            unset($value['memory_cost']);
        }
    
        if (isset($value['time_cost'])) {
            $this->timeCost = $value['time_cost'];
            unset($value['time_cost']);
        }
    
        if (isset($value['id'])) {
            $this->id = $value['id'];
            unset($value['id']);
        }
    
        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }
    
    
    public function toArray(): array
    {
        $output = [];
        if (null !== $this->algorithm) {
            $output['algorithm'] = $this->algorithm;
        }
        if (null !== $this->migrateFrom) {
            $output['migrate_from'] = $this->migrateFrom;
        }
        if (null !== $this->hashAlgorithm) {
            $output['hash_algorithm'] = $this->hashAlgorithm;
        }
        if (null !== $this->keyLength) {
            $output['key_length'] = $this->keyLength;
        }
        if (null !== $this->ignoreCase) {
            $output['ignore_case'] = $this->ignoreCase;
        }
        if (null !== $this->encodeAsBase64) {
            $output['encode_as_base64'] = $this->encodeAsBase64;
        }
        if (null !== $this->iterations) {
            $output['iterations'] = $this->iterations;
        }
        if (null !== $this->cost) {
            $output['cost'] = $this->cost;
        }
        if (null !== $this->memoryCost) {
            $output['memory_cost'] = $this->memoryCost;
        }
        if (null !== $this->timeCost) {
            $output['time_cost'] = $this->timeCost;
        }
        if (null !== $this->id) {
            $output['id'] = $this->id;
        }
    
        return $output;
    }
    

}
