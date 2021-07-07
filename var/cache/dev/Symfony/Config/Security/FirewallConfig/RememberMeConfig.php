<?php

namespace Symfony\Config\Security\FirewallConfig;

require_once __DIR__.\DIRECTORY_SEPARATOR.'RememberMe'.\DIRECTORY_SEPARATOR.'TokenProviderConfig.php';

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class RememberMeConfig 
{
    private $secret;
    private $service;
    private $userProviders;
    private $catchExceptions;
    private $signatureProperties;
    private $tokenProvider;
    private $tokenVerifier;
    private $name;
    private $lifetime;
    private $path;
    private $domain;
    private $secure;
    private $httponly;
    private $samesite;
    private $alwaysRememberMe;
    private $rememberMeParameter;
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function secret($value): self
    {
        $this->secret = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function service($value): self
    {
        $this->service = $value;
    
        return $this;
    }
    
    /**
     * @param ParamConfigurator|list<mixed|ParamConfigurator> $value
     * @return $this
     */
    public function userProviders($value): self
    {
        $this->userProviders = $value;
    
        return $this;
    }
    
    /**
     * @default true
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function catchExceptions($value): self
    {
        $this->catchExceptions = $value;
    
        return $this;
    }
    
    /**
     * @param ParamConfigurator|list<mixed|ParamConfigurator> $value
     * @return $this
     */
    public function signatureProperties($value): self
    {
        $this->signatureProperties = $value;
    
        return $this;
    }
    
    public function tokenProvider(array $value = []): \Symfony\Config\Security\FirewallConfig\RememberMe\TokenProviderConfig
    {
        if (null === $this->tokenProvider) {
            $this->tokenProvider = new \Symfony\Config\Security\FirewallConfig\RememberMe\TokenProviderConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "tokenProvider()" has already been initialized. You cannot pass values the second time you call tokenProvider().');
        }
    
        return $this->tokenProvider;
    }
    
    /**
     * The service ID of a custom rememberme token verifier.
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function tokenVerifier($value): self
    {
        $this->tokenVerifier = $value;
    
        return $this;
    }
    
    /**
     * @default 'REMEMBERME'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function name($value): self
    {
        $this->name = $value;
    
        return $this;
    }
    
    /**
     * @default 31536000
     * @param ParamConfigurator|int $value
     * @return $this
     */
    public function lifetime($value): self
    {
        $this->lifetime = $value;
    
        return $this;
    }
    
    /**
     * @default '/'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function path($value): self
    {
        $this->path = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function domain($value): self
    {
        $this->domain = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|true|false|'auto' $value
     * @return $this
     */
    public function secure($value): self
    {
        $this->secure = $value;
    
        return $this;
    }
    
    /**
     * @default true
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function httponly($value): self
    {
        $this->httponly = $value;
    
        return $this;
    }
    
    /**
     * @default 'lax'
     * @param ParamConfigurator|NULL|'lax'|'strict'|'none' $value
     * @return $this
     */
    public function samesite($value): self
    {
        $this->samesite = $value;
    
        return $this;
    }
    
    /**
     * @default false
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function alwaysRememberMe($value): self
    {
        $this->alwaysRememberMe = $value;
    
        return $this;
    }
    
    /**
     * @default '_remember_me'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function rememberMeParameter($value): self
    {
        $this->rememberMeParameter = $value;
    
        return $this;
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['secret'])) {
            $this->secret = $value['secret'];
            unset($value['secret']);
        }
    
        if (isset($value['service'])) {
            $this->service = $value['service'];
            unset($value['service']);
        }
    
        if (isset($value['user_providers'])) {
            $this->userProviders = $value['user_providers'];
            unset($value['user_providers']);
        }
    
        if (isset($value['catch_exceptions'])) {
            $this->catchExceptions = $value['catch_exceptions'];
            unset($value['catch_exceptions']);
        }
    
        if (isset($value['signature_properties'])) {
            $this->signatureProperties = $value['signature_properties'];
            unset($value['signature_properties']);
        }
    
        if (isset($value['token_provider'])) {
            $this->tokenProvider = new \Symfony\Config\Security\FirewallConfig\RememberMe\TokenProviderConfig($value['token_provider']);
            unset($value['token_provider']);
        }
    
        if (isset($value['token_verifier'])) {
            $this->tokenVerifier = $value['token_verifier'];
            unset($value['token_verifier']);
        }
    
        if (isset($value['name'])) {
            $this->name = $value['name'];
            unset($value['name']);
        }
    
        if (isset($value['lifetime'])) {
            $this->lifetime = $value['lifetime'];
            unset($value['lifetime']);
        }
    
        if (isset($value['path'])) {
            $this->path = $value['path'];
            unset($value['path']);
        }
    
        if (isset($value['domain'])) {
            $this->domain = $value['domain'];
            unset($value['domain']);
        }
    
        if (isset($value['secure'])) {
            $this->secure = $value['secure'];
            unset($value['secure']);
        }
    
        if (isset($value['httponly'])) {
            $this->httponly = $value['httponly'];
            unset($value['httponly']);
        }
    
        if (isset($value['samesite'])) {
            $this->samesite = $value['samesite'];
            unset($value['samesite']);
        }
    
        if (isset($value['always_remember_me'])) {
            $this->alwaysRememberMe = $value['always_remember_me'];
            unset($value['always_remember_me']);
        }
    
        if (isset($value['remember_me_parameter'])) {
            $this->rememberMeParameter = $value['remember_me_parameter'];
            unset($value['remember_me_parameter']);
        }
    
        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }
    
    
    public function toArray(): array
    {
        $output = [];
        if (null !== $this->secret) {
            $output['secret'] = $this->secret;
        }
        if (null !== $this->service) {
            $output['service'] = $this->service;
        }
        if (null !== $this->userProviders) {
            $output['user_providers'] = $this->userProviders;
        }
        if (null !== $this->catchExceptions) {
            $output['catch_exceptions'] = $this->catchExceptions;
        }
        if (null !== $this->signatureProperties) {
            $output['signature_properties'] = $this->signatureProperties;
        }
        if (null !== $this->tokenProvider) {
            $output['token_provider'] = $this->tokenProvider->toArray();
        }
        if (null !== $this->tokenVerifier) {
            $output['token_verifier'] = $this->tokenVerifier;
        }
        if (null !== $this->name) {
            $output['name'] = $this->name;
        }
        if (null !== $this->lifetime) {
            $output['lifetime'] = $this->lifetime;
        }
        if (null !== $this->path) {
            $output['path'] = $this->path;
        }
        if (null !== $this->domain) {
            $output['domain'] = $this->domain;
        }
        if (null !== $this->secure) {
            $output['secure'] = $this->secure;
        }
        if (null !== $this->httponly) {
            $output['httponly'] = $this->httponly;
        }
        if (null !== $this->samesite) {
            $output['samesite'] = $this->samesite;
        }
        if (null !== $this->alwaysRememberMe) {
            $output['always_remember_me'] = $this->alwaysRememberMe;
        }
        if (null !== $this->rememberMeParameter) {
            $output['remember_me_parameter'] = $this->rememberMeParameter;
        }
    
        return $output;
    }
    

}
