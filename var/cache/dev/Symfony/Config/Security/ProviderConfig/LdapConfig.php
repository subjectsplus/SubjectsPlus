<?php

namespace Symfony\Config\Security\ProviderConfig;


use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class LdapConfig 
{
    private $service;
    private $baseDn;
    private $searchDn;
    private $searchPassword;
    private $extraFields;
    private $defaultRoles;
    private $uidKey;
    private $filter;
    private $passwordAttribute;
    
    /**
     * @default 'ldap'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function service($value): self
    {
        $this->service = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function baseDn($value): self
    {
        $this->baseDn = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function searchDn($value): self
    {
        $this->searchDn = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function searchPassword($value): self
    {
        $this->searchPassword = $value;
    
        return $this;
    }
    
    /**
     * @param ParamConfigurator|list<mixed|ParamConfigurator> $value
     * @return $this
     */
    public function extraFields($value): self
    {
        $this->extraFields = $value;
    
        return $this;
    }
    
    /**
     * @param ParamConfigurator|list<mixed|ParamConfigurator> $value
     * @return $this
     */
    public function defaultRoles($value): self
    {
        $this->defaultRoles = $value;
    
        return $this;
    }
    
    /**
     * @default 'sAMAccountName'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function uidKey($value): self
    {
        $this->uidKey = $value;
    
        return $this;
    }
    
    /**
     * @default '({uid_key}={username})'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function filter($value): self
    {
        $this->filter = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function passwordAttribute($value): self
    {
        $this->passwordAttribute = $value;
    
        return $this;
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['service'])) {
            $this->service = $value['service'];
            unset($value['service']);
        }
    
        if (isset($value['base_dn'])) {
            $this->baseDn = $value['base_dn'];
            unset($value['base_dn']);
        }
    
        if (isset($value['search_dn'])) {
            $this->searchDn = $value['search_dn'];
            unset($value['search_dn']);
        }
    
        if (isset($value['search_password'])) {
            $this->searchPassword = $value['search_password'];
            unset($value['search_password']);
        }
    
        if (isset($value['extra_fields'])) {
            $this->extraFields = $value['extra_fields'];
            unset($value['extra_fields']);
        }
    
        if (isset($value['default_roles'])) {
            $this->defaultRoles = $value['default_roles'];
            unset($value['default_roles']);
        }
    
        if (isset($value['uid_key'])) {
            $this->uidKey = $value['uid_key'];
            unset($value['uid_key']);
        }
    
        if (isset($value['filter'])) {
            $this->filter = $value['filter'];
            unset($value['filter']);
        }
    
        if (isset($value['password_attribute'])) {
            $this->passwordAttribute = $value['password_attribute'];
            unset($value['password_attribute']);
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
        if (null !== $this->baseDn) {
            $output['base_dn'] = $this->baseDn;
        }
        if (null !== $this->searchDn) {
            $output['search_dn'] = $this->searchDn;
        }
        if (null !== $this->searchPassword) {
            $output['search_password'] = $this->searchPassword;
        }
        if (null !== $this->extraFields) {
            $output['extra_fields'] = $this->extraFields;
        }
        if (null !== $this->defaultRoles) {
            $output['default_roles'] = $this->defaultRoles;
        }
        if (null !== $this->uidKey) {
            $output['uid_key'] = $this->uidKey;
        }
        if (null !== $this->filter) {
            $output['filter'] = $this->filter;
        }
        if (null !== $this->passwordAttribute) {
            $output['password_attribute'] = $this->passwordAttribute;
        }
    
        return $output;
    }
    

}
