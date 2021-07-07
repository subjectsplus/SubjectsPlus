<?php

namespace Symfony\Config\Security;

require_once __DIR__.\DIRECTORY_SEPARATOR.'FirewallConfig'.\DIRECTORY_SEPARATOR.'LogoutConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'FirewallConfig'.\DIRECTORY_SEPARATOR.'SwitchUserConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'FirewallConfig'.\DIRECTORY_SEPARATOR.'X509Config.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'FirewallConfig'.\DIRECTORY_SEPARATOR.'RemoteUserConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'FirewallConfig'.\DIRECTORY_SEPARATOR.'GuardConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'FirewallConfig'.\DIRECTORY_SEPARATOR.'LoginThrottlingConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'FirewallConfig'.\DIRECTORY_SEPARATOR.'FormLoginConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'FirewallConfig'.\DIRECTORY_SEPARATOR.'FormLoginLdapConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'FirewallConfig'.\DIRECTORY_SEPARATOR.'JsonLoginConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'FirewallConfig'.\DIRECTORY_SEPARATOR.'JsonLoginLdapConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'FirewallConfig'.\DIRECTORY_SEPARATOR.'LoginLinkConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'FirewallConfig'.\DIRECTORY_SEPARATOR.'HttpBasicConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'FirewallConfig'.\DIRECTORY_SEPARATOR.'HttpBasicLdapConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'FirewallConfig'.\DIRECTORY_SEPARATOR.'RememberMeConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'FirewallConfig'.\DIRECTORY_SEPARATOR.'AnonymousConfig.php';

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class FirewallConfig 
{
    private $pattern;
    private $host;
    private $methods;
    private $security;
    private $userChecker;
    private $requestMatcher;
    private $accessDeniedUrl;
    private $accessDeniedHandler;
    private $entryPoint;
    private $provider;
    private $stateless;
    private $lazy;
    private $context;
    private $logout;
    private $switchUser;
    private $requiredBadges;
    private $x509;
    private $remoteUser;
    private $guard;
    private $customAuthenticators;
    private $loginThrottling;
    private $formLogin;
    private $formLoginLdap;
    private $jsonLogin;
    private $jsonLoginLdap;
    private $loginLink;
    private $httpBasic;
    private $httpBasicLdap;
    private $rememberMe;
    private $anonymous;
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function pattern($value): self
    {
        $this->pattern = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function host($value): self
    {
        $this->host = $value;
    
        return $this;
    }
    
    /**
     * @param ParamConfigurator|list<mixed|ParamConfigurator> $value
     * @return $this
     */
    public function methods($value): self
    {
        $this->methods = $value;
    
        return $this;
    }
    
    /**
     * @default true
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function security($value): self
    {
        $this->security = $value;
    
        return $this;
    }
    
    /**
     * The UserChecker to use when authenticating users in this firewall.
     * @default 'security.user_checker'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function userChecker($value): self
    {
        $this->userChecker = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function requestMatcher($value): self
    {
        $this->requestMatcher = $value;
    
        return $this;
    }
    
    /**
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
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function accessDeniedHandler($value): self
    {
        $this->accessDeniedHandler = $value;
    
        return $this;
    }
    
    /**
     * An enabled authenticator name or a service id that implements "Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface"
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function entryPoint($value): self
    {
        $this->entryPoint = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function provider($value): self
    {
        $this->provider = $value;
    
        return $this;
    }
    
    /**
     * @default false
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function stateless($value): self
    {
        $this->stateless = $value;
    
        return $this;
    }
    
    /**
     * @default false
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function lazy($value): self
    {
        $this->lazy = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function context($value): self
    {
        $this->context = $value;
    
        return $this;
    }
    
    public function logout(array $value = []): \Symfony\Config\Security\FirewallConfig\LogoutConfig
    {
        if (null === $this->logout) {
            $this->logout = new \Symfony\Config\Security\FirewallConfig\LogoutConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "logout()" has already been initialized. You cannot pass values the second time you call logout().');
        }
    
        return $this->logout;
    }
    
    public function switchUser(array $value = []): \Symfony\Config\Security\FirewallConfig\SwitchUserConfig
    {
        if (null === $this->switchUser) {
            $this->switchUser = new \Symfony\Config\Security\FirewallConfig\SwitchUserConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "switchUser()" has already been initialized. You cannot pass values the second time you call switchUser().');
        }
    
        return $this->switchUser;
    }
    
    /**
     * @param ParamConfigurator|list<mixed|ParamConfigurator> $value
     * @return $this
     */
    public function requiredBadges($value): self
    {
        $this->requiredBadges = $value;
    
        return $this;
    }
    
    public function x509(array $value = []): \Symfony\Config\Security\FirewallConfig\X509Config
    {
        if (null === $this->x509) {
            $this->x509 = new \Symfony\Config\Security\FirewallConfig\X509Config($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "x509()" has already been initialized. You cannot pass values the second time you call x509().');
        }
    
        return $this->x509;
    }
    
    public function remoteUser(array $value = []): \Symfony\Config\Security\FirewallConfig\RemoteUserConfig
    {
        if (null === $this->remoteUser) {
            $this->remoteUser = new \Symfony\Config\Security\FirewallConfig\RemoteUserConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "remoteUser()" has already been initialized. You cannot pass values the second time you call remoteUser().');
        }
    
        return $this->remoteUser;
    }
    
    public function guard(array $value = []): \Symfony\Config\Security\FirewallConfig\GuardConfig
    {
        if (null === $this->guard) {
            $this->guard = new \Symfony\Config\Security\FirewallConfig\GuardConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "guard()" has already been initialized. You cannot pass values the second time you call guard().');
        }
    
        return $this->guard;
    }
    
    /**
     * @param ParamConfigurator|list<mixed|ParamConfigurator> $value
     * @return $this
     */
    public function customAuthenticators($value): self
    {
        $this->customAuthenticators = $value;
    
        return $this;
    }
    
    public function loginThrottling(array $value = []): \Symfony\Config\Security\FirewallConfig\LoginThrottlingConfig
    {
        if (null === $this->loginThrottling) {
            $this->loginThrottling = new \Symfony\Config\Security\FirewallConfig\LoginThrottlingConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "loginThrottling()" has already been initialized. You cannot pass values the second time you call loginThrottling().');
        }
    
        return $this->loginThrottling;
    }
    
    public function formLogin(array $value = []): \Symfony\Config\Security\FirewallConfig\FormLoginConfig
    {
        if (null === $this->formLogin) {
            $this->formLogin = new \Symfony\Config\Security\FirewallConfig\FormLoginConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "formLogin()" has already been initialized. You cannot pass values the second time you call formLogin().');
        }
    
        return $this->formLogin;
    }
    
    public function formLoginLdap(array $value = []): \Symfony\Config\Security\FirewallConfig\FormLoginLdapConfig
    {
        if (null === $this->formLoginLdap) {
            $this->formLoginLdap = new \Symfony\Config\Security\FirewallConfig\FormLoginLdapConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "formLoginLdap()" has already been initialized. You cannot pass values the second time you call formLoginLdap().');
        }
    
        return $this->formLoginLdap;
    }
    
    public function jsonLogin(array $value = []): \Symfony\Config\Security\FirewallConfig\JsonLoginConfig
    {
        if (null === $this->jsonLogin) {
            $this->jsonLogin = new \Symfony\Config\Security\FirewallConfig\JsonLoginConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "jsonLogin()" has already been initialized. You cannot pass values the second time you call jsonLogin().');
        }
    
        return $this->jsonLogin;
    }
    
    public function jsonLoginLdap(array $value = []): \Symfony\Config\Security\FirewallConfig\JsonLoginLdapConfig
    {
        if (null === $this->jsonLoginLdap) {
            $this->jsonLoginLdap = new \Symfony\Config\Security\FirewallConfig\JsonLoginLdapConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "jsonLoginLdap()" has already been initialized. You cannot pass values the second time you call jsonLoginLdap().');
        }
    
        return $this->jsonLoginLdap;
    }
    
    public function loginLink(array $value = []): \Symfony\Config\Security\FirewallConfig\LoginLinkConfig
    {
        if (null === $this->loginLink) {
            $this->loginLink = new \Symfony\Config\Security\FirewallConfig\LoginLinkConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "loginLink()" has already been initialized. You cannot pass values the second time you call loginLink().');
        }
    
        return $this->loginLink;
    }
    
    public function httpBasic(array $value = []): \Symfony\Config\Security\FirewallConfig\HttpBasicConfig
    {
        if (null === $this->httpBasic) {
            $this->httpBasic = new \Symfony\Config\Security\FirewallConfig\HttpBasicConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "httpBasic()" has already been initialized. You cannot pass values the second time you call httpBasic().');
        }
    
        return $this->httpBasic;
    }
    
    public function httpBasicLdap(array $value = []): \Symfony\Config\Security\FirewallConfig\HttpBasicLdapConfig
    {
        if (null === $this->httpBasicLdap) {
            $this->httpBasicLdap = new \Symfony\Config\Security\FirewallConfig\HttpBasicLdapConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "httpBasicLdap()" has already been initialized. You cannot pass values the second time you call httpBasicLdap().');
        }
    
        return $this->httpBasicLdap;
    }
    
    public function rememberMe(array $value = []): \Symfony\Config\Security\FirewallConfig\RememberMeConfig
    {
        if (null === $this->rememberMe) {
            $this->rememberMe = new \Symfony\Config\Security\FirewallConfig\RememberMeConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "rememberMe()" has already been initialized. You cannot pass values the second time you call rememberMe().');
        }
    
        return $this->rememberMe;
    }
    
    public function anonymous(array $value = []): \Symfony\Config\Security\FirewallConfig\AnonymousConfig
    {
        if (null === $this->anonymous) {
            $this->anonymous = new \Symfony\Config\Security\FirewallConfig\AnonymousConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "anonymous()" has already been initialized. You cannot pass values the second time you call anonymous().');
        }
    
        return $this->anonymous;
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['pattern'])) {
            $this->pattern = $value['pattern'];
            unset($value['pattern']);
        }
    
        if (isset($value['host'])) {
            $this->host = $value['host'];
            unset($value['host']);
        }
    
        if (isset($value['methods'])) {
            $this->methods = $value['methods'];
            unset($value['methods']);
        }
    
        if (isset($value['security'])) {
            $this->security = $value['security'];
            unset($value['security']);
        }
    
        if (isset($value['user_checker'])) {
            $this->userChecker = $value['user_checker'];
            unset($value['user_checker']);
        }
    
        if (isset($value['request_matcher'])) {
            $this->requestMatcher = $value['request_matcher'];
            unset($value['request_matcher']);
        }
    
        if (isset($value['access_denied_url'])) {
            $this->accessDeniedUrl = $value['access_denied_url'];
            unset($value['access_denied_url']);
        }
    
        if (isset($value['access_denied_handler'])) {
            $this->accessDeniedHandler = $value['access_denied_handler'];
            unset($value['access_denied_handler']);
        }
    
        if (isset($value['entry_point'])) {
            $this->entryPoint = $value['entry_point'];
            unset($value['entry_point']);
        }
    
        if (isset($value['provider'])) {
            $this->provider = $value['provider'];
            unset($value['provider']);
        }
    
        if (isset($value['stateless'])) {
            $this->stateless = $value['stateless'];
            unset($value['stateless']);
        }
    
        if (isset($value['lazy'])) {
            $this->lazy = $value['lazy'];
            unset($value['lazy']);
        }
    
        if (isset($value['context'])) {
            $this->context = $value['context'];
            unset($value['context']);
        }
    
        if (isset($value['logout'])) {
            $this->logout = new \Symfony\Config\Security\FirewallConfig\LogoutConfig($value['logout']);
            unset($value['logout']);
        }
    
        if (isset($value['switch_user'])) {
            $this->switchUser = new \Symfony\Config\Security\FirewallConfig\SwitchUserConfig($value['switch_user']);
            unset($value['switch_user']);
        }
    
        if (isset($value['required_badges'])) {
            $this->requiredBadges = $value['required_badges'];
            unset($value['required_badges']);
        }
    
        if (isset($value['x509'])) {
            $this->x509 = new \Symfony\Config\Security\FirewallConfig\X509Config($value['x509']);
            unset($value['x509']);
        }
    
        if (isset($value['remote_user'])) {
            $this->remoteUser = new \Symfony\Config\Security\FirewallConfig\RemoteUserConfig($value['remote_user']);
            unset($value['remote_user']);
        }
    
        if (isset($value['guard'])) {
            $this->guard = new \Symfony\Config\Security\FirewallConfig\GuardConfig($value['guard']);
            unset($value['guard']);
        }
    
        if (isset($value['custom_authenticators'])) {
            $this->customAuthenticators = $value['custom_authenticators'];
            unset($value['custom_authenticators']);
        }
    
        if (isset($value['login_throttling'])) {
            $this->loginThrottling = new \Symfony\Config\Security\FirewallConfig\LoginThrottlingConfig($value['login_throttling']);
            unset($value['login_throttling']);
        }
    
        if (isset($value['form_login'])) {
            $this->formLogin = new \Symfony\Config\Security\FirewallConfig\FormLoginConfig($value['form_login']);
            unset($value['form_login']);
        }
    
        if (isset($value['form_login_ldap'])) {
            $this->formLoginLdap = new \Symfony\Config\Security\FirewallConfig\FormLoginLdapConfig($value['form_login_ldap']);
            unset($value['form_login_ldap']);
        }
    
        if (isset($value['json_login'])) {
            $this->jsonLogin = new \Symfony\Config\Security\FirewallConfig\JsonLoginConfig($value['json_login']);
            unset($value['json_login']);
        }
    
        if (isset($value['json_login_ldap'])) {
            $this->jsonLoginLdap = new \Symfony\Config\Security\FirewallConfig\JsonLoginLdapConfig($value['json_login_ldap']);
            unset($value['json_login_ldap']);
        }
    
        if (isset($value['login_link'])) {
            $this->loginLink = new \Symfony\Config\Security\FirewallConfig\LoginLinkConfig($value['login_link']);
            unset($value['login_link']);
        }
    
        if (isset($value['http_basic'])) {
            $this->httpBasic = new \Symfony\Config\Security\FirewallConfig\HttpBasicConfig($value['http_basic']);
            unset($value['http_basic']);
        }
    
        if (isset($value['http_basic_ldap'])) {
            $this->httpBasicLdap = new \Symfony\Config\Security\FirewallConfig\HttpBasicLdapConfig($value['http_basic_ldap']);
            unset($value['http_basic_ldap']);
        }
    
        if (isset($value['remember_me'])) {
            $this->rememberMe = new \Symfony\Config\Security\FirewallConfig\RememberMeConfig($value['remember_me']);
            unset($value['remember_me']);
        }
    
        if (isset($value['anonymous'])) {
            $this->anonymous = new \Symfony\Config\Security\FirewallConfig\AnonymousConfig($value['anonymous']);
            unset($value['anonymous']);
        }
    
        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }
    
    
    public function toArray(): array
    {
        $output = [];
        if (null !== $this->pattern) {
            $output['pattern'] = $this->pattern;
        }
        if (null !== $this->host) {
            $output['host'] = $this->host;
        }
        if (null !== $this->methods) {
            $output['methods'] = $this->methods;
        }
        if (null !== $this->security) {
            $output['security'] = $this->security;
        }
        if (null !== $this->userChecker) {
            $output['user_checker'] = $this->userChecker;
        }
        if (null !== $this->requestMatcher) {
            $output['request_matcher'] = $this->requestMatcher;
        }
        if (null !== $this->accessDeniedUrl) {
            $output['access_denied_url'] = $this->accessDeniedUrl;
        }
        if (null !== $this->accessDeniedHandler) {
            $output['access_denied_handler'] = $this->accessDeniedHandler;
        }
        if (null !== $this->entryPoint) {
            $output['entry_point'] = $this->entryPoint;
        }
        if (null !== $this->provider) {
            $output['provider'] = $this->provider;
        }
        if (null !== $this->stateless) {
            $output['stateless'] = $this->stateless;
        }
        if (null !== $this->lazy) {
            $output['lazy'] = $this->lazy;
        }
        if (null !== $this->context) {
            $output['context'] = $this->context;
        }
        if (null !== $this->logout) {
            $output['logout'] = $this->logout->toArray();
        }
        if (null !== $this->switchUser) {
            $output['switch_user'] = $this->switchUser->toArray();
        }
        if (null !== $this->requiredBadges) {
            $output['required_badges'] = $this->requiredBadges;
        }
        if (null !== $this->x509) {
            $output['x509'] = $this->x509->toArray();
        }
        if (null !== $this->remoteUser) {
            $output['remote_user'] = $this->remoteUser->toArray();
        }
        if (null !== $this->guard) {
            $output['guard'] = $this->guard->toArray();
        }
        if (null !== $this->customAuthenticators) {
            $output['custom_authenticators'] = $this->customAuthenticators;
        }
        if (null !== $this->loginThrottling) {
            $output['login_throttling'] = $this->loginThrottling->toArray();
        }
        if (null !== $this->formLogin) {
            $output['form_login'] = $this->formLogin->toArray();
        }
        if (null !== $this->formLoginLdap) {
            $output['form_login_ldap'] = $this->formLoginLdap->toArray();
        }
        if (null !== $this->jsonLogin) {
            $output['json_login'] = $this->jsonLogin->toArray();
        }
        if (null !== $this->jsonLoginLdap) {
            $output['json_login_ldap'] = $this->jsonLoginLdap->toArray();
        }
        if (null !== $this->loginLink) {
            $output['login_link'] = $this->loginLink->toArray();
        }
        if (null !== $this->httpBasic) {
            $output['http_basic'] = $this->httpBasic->toArray();
        }
        if (null !== $this->httpBasicLdap) {
            $output['http_basic_ldap'] = $this->httpBasicLdap->toArray();
        }
        if (null !== $this->rememberMe) {
            $output['remember_me'] = $this->rememberMe->toArray();
        }
        if (null !== $this->anonymous) {
            $output['anonymous'] = $this->anonymous->toArray();
        }
    
        return $output;
    }
    

}
