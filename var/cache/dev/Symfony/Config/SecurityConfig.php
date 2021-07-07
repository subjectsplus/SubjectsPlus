<?php

namespace Symfony\Config;

require_once __DIR__.\DIRECTORY_SEPARATOR.'Security'.\DIRECTORY_SEPARATOR.'AccessDecisionManagerConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'Security'.\DIRECTORY_SEPARATOR.'EncoderConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'Security'.\DIRECTORY_SEPARATOR.'PasswordHasherConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'Security'.\DIRECTORY_SEPARATOR.'ProviderConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'Security'.\DIRECTORY_SEPARATOR.'FirewallConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'Security'.\DIRECTORY_SEPARATOR.'AccessControlConfig.php';

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class SecurityConfig implements \Symfony\Component\Config\Builder\ConfigBuilderInterface
{
    private $accessDeniedUrl;
    private $sessionFixationStrategy;
    private $hideUserNotFound;
    private $alwaysAuthenticateBeforeGranting;
    private $eraseCredentials;
    private $enableAuthenticatorManager;
    private $accessDecisionManager;
    private $encoders;
    private $passwordHashers;
    private $providers;
    private $firewalls;
    private $accessControl;
    private $roleHierarchy;
    
    /**
     * @example /foo/error403
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function accessDeniedUrl($value): self
    {
        $this->accessDeniedUrl = $value;
    
        return $this;
    }
    
    /**
     * @default 'migrate'
     * @param ParamConfigurator|'none'|'migrate'|'invalidate' $value
     * @return $this
     */
    public function sessionFixationStrategy($value): self
    {
        $this->sessionFixationStrategy = $value;
    
        return $this;
    }
    
    /**
     * @default true
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function hideUserNotFound($value): self
    {
        $this->hideUserNotFound = $value;
    
        return $this;
    }
    
    /**
     * @default false
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function alwaysAuthenticateBeforeGranting($value): self
    {
        $this->alwaysAuthenticateBeforeGranting = $value;
    
        return $this;
    }
    
    /**
     * @default true
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function eraseCredentials($value): self
    {
        $this->eraseCredentials = $value;
    
        return $this;
    }
    
    /**
     * Enables the new Symfony Security system based on Authenticators, all used authenticators must support this before enabling this.
     * @default false
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function enableAuthenticatorManager($value): self
    {
        $this->enableAuthenticatorManager = $value;
    
        return $this;
    }
    
    public function accessDecisionManager(array $value = []): \Symfony\Config\Security\AccessDecisionManagerConfig
    {
        if (null === $this->accessDecisionManager) {
            $this->accessDecisionManager = new \Symfony\Config\Security\AccessDecisionManagerConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "accessDecisionManager()" has already been initialized. You cannot pass values the second time you call accessDecisionManager().');
        }
    
        return $this->accessDecisionManager;
    }
    
    public function encoder(string $class, array $value = []): \Symfony\Config\Security\EncoderConfig
    {
        if (!isset($this->encoders[$class])) {
            return $this->encoders[$class] = new \Symfony\Config\Security\EncoderConfig($value);
        }
        if ([] === $value) {
            return $this->encoders[$class];
        }
    
        throw new InvalidConfigurationException('The node created by "encoder()" has already been initialized. You cannot pass values the second time you call encoder().');
    }
    
    public function passwordHasher(string $class, array $value = []): \Symfony\Config\Security\PasswordHasherConfig
    {
        if (!isset($this->passwordHashers[$class])) {
            return $this->passwordHashers[$class] = new \Symfony\Config\Security\PasswordHasherConfig($value);
        }
        if ([] === $value) {
            return $this->passwordHashers[$class];
        }
    
        throw new InvalidConfigurationException('The node created by "passwordHasher()" has already been initialized. You cannot pass values the second time you call passwordHasher().');
    }
    
    public function provider(string $name, array $value = []): \Symfony\Config\Security\ProviderConfig
    {
        if (!isset($this->providers[$name])) {
            return $this->providers[$name] = new \Symfony\Config\Security\ProviderConfig($value);
        }
        if ([] === $value) {
            return $this->providers[$name];
        }
    
        throw new InvalidConfigurationException('The node created by "provider()" has already been initialized. You cannot pass values the second time you call provider().');
    }
    
    public function firewall(string $name, array $value = []): \Symfony\Config\Security\FirewallConfig
    {
        if (!isset($this->firewalls[$name])) {
            return $this->firewalls[$name] = new \Symfony\Config\Security\FirewallConfig($value);
        }
        if ([] === $value) {
            return $this->firewalls[$name];
        }
    
        throw new InvalidConfigurationException('The node created by "firewall()" has already been initialized. You cannot pass values the second time you call firewall().');
    }
    
    public function accessControl(array $value = []): \Symfony\Config\Security\AccessControlConfig
    {
        return $this->accessControl[] = new \Symfony\Config\Security\AccessControlConfig($value);
    }
    
    /**
     * @param ParamConfigurator|array $value
     * @return $this
     */
    public function roleHierarchy(string $id, $value): self
    {
        $this->roleHierarchy[$id] = $value;
    
        return $this;
    }
    
    public function getExtensionAlias(): string
    {
        return 'security';
    }
            
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['access_denied_url'])) {
            $this->accessDeniedUrl = $value['access_denied_url'];
            unset($value['access_denied_url']);
        }
    
        if (isset($value['session_fixation_strategy'])) {
            $this->sessionFixationStrategy = $value['session_fixation_strategy'];
            unset($value['session_fixation_strategy']);
        }
    
        if (isset($value['hide_user_not_found'])) {
            $this->hideUserNotFound = $value['hide_user_not_found'];
            unset($value['hide_user_not_found']);
        }
    
        if (isset($value['always_authenticate_before_granting'])) {
            $this->alwaysAuthenticateBeforeGranting = $value['always_authenticate_before_granting'];
            unset($value['always_authenticate_before_granting']);
        }
    
        if (isset($value['erase_credentials'])) {
            $this->eraseCredentials = $value['erase_credentials'];
            unset($value['erase_credentials']);
        }
    
        if (isset($value['enable_authenticator_manager'])) {
            $this->enableAuthenticatorManager = $value['enable_authenticator_manager'];
            unset($value['enable_authenticator_manager']);
        }
    
        if (isset($value['access_decision_manager'])) {
            $this->accessDecisionManager = new \Symfony\Config\Security\AccessDecisionManagerConfig($value['access_decision_manager']);
            unset($value['access_decision_manager']);
        }
    
        if (isset($value['encoders'])) {
            $this->encoders = array_map(function ($v) { return new \Symfony\Config\Security\EncoderConfig($v); }, $value['encoders']);
            unset($value['encoders']);
        }
    
        if (isset($value['password_hashers'])) {
            $this->passwordHashers = array_map(function ($v) { return new \Symfony\Config\Security\PasswordHasherConfig($v); }, $value['password_hashers']);
            unset($value['password_hashers']);
        }
    
        if (isset($value['providers'])) {
            $this->providers = array_map(function ($v) { return new \Symfony\Config\Security\ProviderConfig($v); }, $value['providers']);
            unset($value['providers']);
        }
    
        if (isset($value['firewalls'])) {
            $this->firewalls = array_map(function ($v) { return new \Symfony\Config\Security\FirewallConfig($v); }, $value['firewalls']);
            unset($value['firewalls']);
        }
    
        if (isset($value['access_control'])) {
            $this->accessControl = array_map(function ($v) { return new \Symfony\Config\Security\AccessControlConfig($v); }, $value['access_control']);
            unset($value['access_control']);
        }
    
        if (isset($value['role_hierarchy'])) {
            $this->roleHierarchy = $value['role_hierarchy'];
            unset($value['role_hierarchy']);
        }
    
        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }
    
    
    public function toArray(): array
    {
        $output = [];
        if (null !== $this->accessDeniedUrl) {
            $output['access_denied_url'] = $this->accessDeniedUrl;
        }
        if (null !== $this->sessionFixationStrategy) {
            $output['session_fixation_strategy'] = $this->sessionFixationStrategy;
        }
        if (null !== $this->hideUserNotFound) {
            $output['hide_user_not_found'] = $this->hideUserNotFound;
        }
        if (null !== $this->alwaysAuthenticateBeforeGranting) {
            $output['always_authenticate_before_granting'] = $this->alwaysAuthenticateBeforeGranting;
        }
        if (null !== $this->eraseCredentials) {
            $output['erase_credentials'] = $this->eraseCredentials;
        }
        if (null !== $this->enableAuthenticatorManager) {
            $output['enable_authenticator_manager'] = $this->enableAuthenticatorManager;
        }
        if (null !== $this->accessDecisionManager) {
            $output['access_decision_manager'] = $this->accessDecisionManager->toArray();
        }
        if (null !== $this->encoders) {
            $output['encoders'] = array_map(function ($v) { return $v->toArray(); }, $this->encoders);
        }
        if (null !== $this->passwordHashers) {
            $output['password_hashers'] = array_map(function ($v) { return $v->toArray(); }, $this->passwordHashers);
        }
        if (null !== $this->providers) {
            $output['providers'] = array_map(function ($v) { return $v->toArray(); }, $this->providers);
        }
        if (null !== $this->firewalls) {
            $output['firewalls'] = array_map(function ($v) { return $v->toArray(); }, $this->firewalls);
        }
        if (null !== $this->accessControl) {
            $output['access_control'] = array_map(function ($v) { return $v->toArray(); }, $this->accessControl);
        }
        if (null !== $this->roleHierarchy) {
            $output['role_hierarchy'] = $this->roleHierarchy;
        }
    
        return $output;
    }
    

}
