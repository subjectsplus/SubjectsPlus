<?php

namespace Symfony\Config;

require_once __DIR__.\DIRECTORY_SEPARATOR.'Framework'.\DIRECTORY_SEPARATOR.'CsrfProtectionConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'Framework'.\DIRECTORY_SEPARATOR.'FormConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'Framework'.\DIRECTORY_SEPARATOR.'HttpCacheConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'Framework'.\DIRECTORY_SEPARATOR.'EsiConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'Framework'.\DIRECTORY_SEPARATOR.'SsiConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'Framework'.\DIRECTORY_SEPARATOR.'FragmentsConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'Framework'.\DIRECTORY_SEPARATOR.'ProfilerConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'Framework'.\DIRECTORY_SEPARATOR.'WorkflowsConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'Framework'.\DIRECTORY_SEPARATOR.'RouterConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'Framework'.\DIRECTORY_SEPARATOR.'SessionConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'Framework'.\DIRECTORY_SEPARATOR.'RequestConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'Framework'.\DIRECTORY_SEPARATOR.'AssetsConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'Framework'.\DIRECTORY_SEPARATOR.'TranslatorConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'Framework'.\DIRECTORY_SEPARATOR.'ValidationConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'Framework'.\DIRECTORY_SEPARATOR.'AnnotationsConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'Framework'.\DIRECTORY_SEPARATOR.'SerializerConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'Framework'.\DIRECTORY_SEPARATOR.'PropertyAccessConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'Framework'.\DIRECTORY_SEPARATOR.'PropertyInfoConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'Framework'.\DIRECTORY_SEPARATOR.'CacheConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'Framework'.\DIRECTORY_SEPARATOR.'PhpErrorsConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'Framework'.\DIRECTORY_SEPARATOR.'WebLinkConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'Framework'.\DIRECTORY_SEPARATOR.'LockConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'Framework'.\DIRECTORY_SEPARATOR.'MessengerConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'Framework'.\DIRECTORY_SEPARATOR.'HttpClientConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'Framework'.\DIRECTORY_SEPARATOR.'MailerConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'Framework'.\DIRECTORY_SEPARATOR.'SecretsConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'Framework'.\DIRECTORY_SEPARATOR.'NotifierConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'Framework'.\DIRECTORY_SEPARATOR.'RateLimiterConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'Framework'.\DIRECTORY_SEPARATOR.'UidConfig.php';

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class FrameworkConfig implements \Symfony\Component\Config\Builder\ConfigBuilderInterface
{
    private $secret;
    private $httpMethodOverride;
    private $ide;
    private $test;
    private $defaultLocale;
    private $trustedHosts;
    private $trustedProxies;
    private $trustedHeaders;
    private $errorController;
    private $csrfProtection;
    private $form;
    private $httpCache;
    private $esi;
    private $ssi;
    private $fragments;
    private $profiler;
    private $workflows;
    private $router;
    private $session;
    private $request;
    private $assets;
    private $translator;
    private $validation;
    private $annotations;
    private $serializer;
    private $propertyAccess;
    private $propertyInfo;
    private $cache;
    private $phpErrors;
    private $webLink;
    private $lock;
    private $messenger;
    private $disallowSearchEngineIndex;
    private $httpClient;
    private $mailer;
    private $secrets;
    private $notifier;
    private $rateLimiter;
    private $uid;
    
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
     * Set true to enable support for the '_method' request parameter to determine the intended HTTP method on POST requests. Note: When using the HttpCache, you need to call the method in your front controller instead
     * @default true
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function httpMethodOverride($value): self
    {
        $this->httpMethodOverride = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function ide($value): self
    {
        $this->ide = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function test($value): self
    {
        $this->test = $value;
    
        return $this;
    }
    
    /**
     * @default 'en'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function defaultLocale($value): self
    {
        $this->defaultLocale = $value;
    
        return $this;
    }
    
    /**
     * @param ParamConfigurator|list<mixed|ParamConfigurator> $value
     * @return $this
     */
    public function trustedHosts($value): self
    {
        $this->trustedHosts = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function trustedProxies($value): self
    {
        $this->trustedProxies = $value;
    
        return $this;
    }
    
    /**
     * @param ParamConfigurator|list<mixed|ParamConfigurator> $value
     * @return $this
     */
    public function trustedHeaders($value): self
    {
        $this->trustedHeaders = $value;
    
        return $this;
    }
    
    /**
     * @default 'error_controller'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function errorController($value): self
    {
        $this->errorController = $value;
    
        return $this;
    }
    
    public function csrfProtection(array $value = []): \Symfony\Config\Framework\CsrfProtectionConfig
    {
        if (null === $this->csrfProtection) {
            $this->csrfProtection = new \Symfony\Config\Framework\CsrfProtectionConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "csrfProtection()" has already been initialized. You cannot pass values the second time you call csrfProtection().');
        }
    
        return $this->csrfProtection;
    }
    
    public function form(array $value = []): \Symfony\Config\Framework\FormConfig
    {
        if (null === $this->form) {
            $this->form = new \Symfony\Config\Framework\FormConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "form()" has already been initialized. You cannot pass values the second time you call form().');
        }
    
        return $this->form;
    }
    
    public function httpCache(array $value = []): \Symfony\Config\Framework\HttpCacheConfig
    {
        if (null === $this->httpCache) {
            $this->httpCache = new \Symfony\Config\Framework\HttpCacheConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "httpCache()" has already been initialized. You cannot pass values the second time you call httpCache().');
        }
    
        return $this->httpCache;
    }
    
    public function esi(array $value = []): \Symfony\Config\Framework\EsiConfig
    {
        if (null === $this->esi) {
            $this->esi = new \Symfony\Config\Framework\EsiConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "esi()" has already been initialized. You cannot pass values the second time you call esi().');
        }
    
        return $this->esi;
    }
    
    public function ssi(array $value = []): \Symfony\Config\Framework\SsiConfig
    {
        if (null === $this->ssi) {
            $this->ssi = new \Symfony\Config\Framework\SsiConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "ssi()" has already been initialized. You cannot pass values the second time you call ssi().');
        }
    
        return $this->ssi;
    }
    
    public function fragments(array $value = []): \Symfony\Config\Framework\FragmentsConfig
    {
        if (null === $this->fragments) {
            $this->fragments = new \Symfony\Config\Framework\FragmentsConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "fragments()" has already been initialized. You cannot pass values the second time you call fragments().');
        }
    
        return $this->fragments;
    }
    
    public function profiler(array $value = []): \Symfony\Config\Framework\ProfilerConfig
    {
        if (null === $this->profiler) {
            $this->profiler = new \Symfony\Config\Framework\ProfilerConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "profiler()" has already been initialized. You cannot pass values the second time you call profiler().');
        }
    
        return $this->profiler;
    }
    
    public function workflows(array $value = []): \Symfony\Config\Framework\WorkflowsConfig
    {
        if (null === $this->workflows) {
            $this->workflows = new \Symfony\Config\Framework\WorkflowsConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "workflows()" has already been initialized. You cannot pass values the second time you call workflows().');
        }
    
        return $this->workflows;
    }
    
    public function router(array $value = []): \Symfony\Config\Framework\RouterConfig
    {
        if (null === $this->router) {
            $this->router = new \Symfony\Config\Framework\RouterConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "router()" has already been initialized. You cannot pass values the second time you call router().');
        }
    
        return $this->router;
    }
    
    public function session(array $value = []): \Symfony\Config\Framework\SessionConfig
    {
        if (null === $this->session) {
            $this->session = new \Symfony\Config\Framework\SessionConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "session()" has already been initialized. You cannot pass values the second time you call session().');
        }
    
        return $this->session;
    }
    
    public function request(array $value = []): \Symfony\Config\Framework\RequestConfig
    {
        if (null === $this->request) {
            $this->request = new \Symfony\Config\Framework\RequestConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "request()" has already been initialized. You cannot pass values the second time you call request().');
        }
    
        return $this->request;
    }
    
    public function assets(array $value = []): \Symfony\Config\Framework\AssetsConfig
    {
        if (null === $this->assets) {
            $this->assets = new \Symfony\Config\Framework\AssetsConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "assets()" has already been initialized. You cannot pass values the second time you call assets().');
        }
    
        return $this->assets;
    }
    
    public function translator(array $value = []): \Symfony\Config\Framework\TranslatorConfig
    {
        if (null === $this->translator) {
            $this->translator = new \Symfony\Config\Framework\TranslatorConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "translator()" has already been initialized. You cannot pass values the second time you call translator().');
        }
    
        return $this->translator;
    }
    
    public function validation(array $value = []): \Symfony\Config\Framework\ValidationConfig
    {
        if (null === $this->validation) {
            $this->validation = new \Symfony\Config\Framework\ValidationConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "validation()" has already been initialized. You cannot pass values the second time you call validation().');
        }
    
        return $this->validation;
    }
    
    public function annotations(array $value = []): \Symfony\Config\Framework\AnnotationsConfig
    {
        if (null === $this->annotations) {
            $this->annotations = new \Symfony\Config\Framework\AnnotationsConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "annotations()" has already been initialized. You cannot pass values the second time you call annotations().');
        }
    
        return $this->annotations;
    }
    
    public function serializer(array $value = []): \Symfony\Config\Framework\SerializerConfig
    {
        if (null === $this->serializer) {
            $this->serializer = new \Symfony\Config\Framework\SerializerConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "serializer()" has already been initialized. You cannot pass values the second time you call serializer().');
        }
    
        return $this->serializer;
    }
    
    public function propertyAccess(array $value = []): \Symfony\Config\Framework\PropertyAccessConfig
    {
        if (null === $this->propertyAccess) {
            $this->propertyAccess = new \Symfony\Config\Framework\PropertyAccessConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "propertyAccess()" has already been initialized. You cannot pass values the second time you call propertyAccess().');
        }
    
        return $this->propertyAccess;
    }
    
    public function propertyInfo(array $value = []): \Symfony\Config\Framework\PropertyInfoConfig
    {
        if (null === $this->propertyInfo) {
            $this->propertyInfo = new \Symfony\Config\Framework\PropertyInfoConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "propertyInfo()" has already been initialized. You cannot pass values the second time you call propertyInfo().');
        }
    
        return $this->propertyInfo;
    }
    
    public function cache(array $value = []): \Symfony\Config\Framework\CacheConfig
    {
        if (null === $this->cache) {
            $this->cache = new \Symfony\Config\Framework\CacheConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "cache()" has already been initialized. You cannot pass values the second time you call cache().');
        }
    
        return $this->cache;
    }
    
    public function phpErrors(array $value = []): \Symfony\Config\Framework\PhpErrorsConfig
    {
        if (null === $this->phpErrors) {
            $this->phpErrors = new \Symfony\Config\Framework\PhpErrorsConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "phpErrors()" has already been initialized. You cannot pass values the second time you call phpErrors().');
        }
    
        return $this->phpErrors;
    }
    
    public function webLink(array $value = []): \Symfony\Config\Framework\WebLinkConfig
    {
        if (null === $this->webLink) {
            $this->webLink = new \Symfony\Config\Framework\WebLinkConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "webLink()" has already been initialized. You cannot pass values the second time you call webLink().');
        }
    
        return $this->webLink;
    }
    
    public function lock(array $value = []): \Symfony\Config\Framework\LockConfig
    {
        if (null === $this->lock) {
            $this->lock = new \Symfony\Config\Framework\LockConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "lock()" has already been initialized. You cannot pass values the second time you call lock().');
        }
    
        return $this->lock;
    }
    
    public function messenger(array $value = []): \Symfony\Config\Framework\MessengerConfig
    {
        if (null === $this->messenger) {
            $this->messenger = new \Symfony\Config\Framework\MessengerConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "messenger()" has already been initialized. You cannot pass values the second time you call messenger().');
        }
    
        return $this->messenger;
    }
    
    /**
     * Enabled by default when debug is enabled.
     * @default true
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function disallowSearchEngineIndex($value): self
    {
        $this->disallowSearchEngineIndex = $value;
    
        return $this;
    }
    
    public function httpClient(array $value = []): \Symfony\Config\Framework\HttpClientConfig
    {
        if (null === $this->httpClient) {
            $this->httpClient = new \Symfony\Config\Framework\HttpClientConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "httpClient()" has already been initialized. You cannot pass values the second time you call httpClient().');
        }
    
        return $this->httpClient;
    }
    
    public function mailer(array $value = []): \Symfony\Config\Framework\MailerConfig
    {
        if (null === $this->mailer) {
            $this->mailer = new \Symfony\Config\Framework\MailerConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "mailer()" has already been initialized. You cannot pass values the second time you call mailer().');
        }
    
        return $this->mailer;
    }
    
    public function secrets(array $value = []): \Symfony\Config\Framework\SecretsConfig
    {
        if (null === $this->secrets) {
            $this->secrets = new \Symfony\Config\Framework\SecretsConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "secrets()" has already been initialized. You cannot pass values the second time you call secrets().');
        }
    
        return $this->secrets;
    }
    
    public function notifier(array $value = []): \Symfony\Config\Framework\NotifierConfig
    {
        if (null === $this->notifier) {
            $this->notifier = new \Symfony\Config\Framework\NotifierConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "notifier()" has already been initialized. You cannot pass values the second time you call notifier().');
        }
    
        return $this->notifier;
    }
    
    public function rateLimiter(array $value = []): \Symfony\Config\Framework\RateLimiterConfig
    {
        if (null === $this->rateLimiter) {
            $this->rateLimiter = new \Symfony\Config\Framework\RateLimiterConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "rateLimiter()" has already been initialized. You cannot pass values the second time you call rateLimiter().');
        }
    
        return $this->rateLimiter;
    }
    
    public function uid(array $value = []): \Symfony\Config\Framework\UidConfig
    {
        if (null === $this->uid) {
            $this->uid = new \Symfony\Config\Framework\UidConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "uid()" has already been initialized. You cannot pass values the second time you call uid().');
        }
    
        return $this->uid;
    }
    
    public function getExtensionAlias(): string
    {
        return 'framework';
    }
            
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['secret'])) {
            $this->secret = $value['secret'];
            unset($value['secret']);
        }
    
        if (isset($value['http_method_override'])) {
            $this->httpMethodOverride = $value['http_method_override'];
            unset($value['http_method_override']);
        }
    
        if (isset($value['ide'])) {
            $this->ide = $value['ide'];
            unset($value['ide']);
        }
    
        if (isset($value['test'])) {
            $this->test = $value['test'];
            unset($value['test']);
        }
    
        if (isset($value['default_locale'])) {
            $this->defaultLocale = $value['default_locale'];
            unset($value['default_locale']);
        }
    
        if (isset($value['trusted_hosts'])) {
            $this->trustedHosts = $value['trusted_hosts'];
            unset($value['trusted_hosts']);
        }
    
        if (isset($value['trusted_proxies'])) {
            $this->trustedProxies = $value['trusted_proxies'];
            unset($value['trusted_proxies']);
        }
    
        if (isset($value['trusted_headers'])) {
            $this->trustedHeaders = $value['trusted_headers'];
            unset($value['trusted_headers']);
        }
    
        if (isset($value['error_controller'])) {
            $this->errorController = $value['error_controller'];
            unset($value['error_controller']);
        }
    
        if (isset($value['csrf_protection'])) {
            $this->csrfProtection = new \Symfony\Config\Framework\CsrfProtectionConfig($value['csrf_protection']);
            unset($value['csrf_protection']);
        }
    
        if (isset($value['form'])) {
            $this->form = new \Symfony\Config\Framework\FormConfig($value['form']);
            unset($value['form']);
        }
    
        if (isset($value['http_cache'])) {
            $this->httpCache = new \Symfony\Config\Framework\HttpCacheConfig($value['http_cache']);
            unset($value['http_cache']);
        }
    
        if (isset($value['esi'])) {
            $this->esi = new \Symfony\Config\Framework\EsiConfig($value['esi']);
            unset($value['esi']);
        }
    
        if (isset($value['ssi'])) {
            $this->ssi = new \Symfony\Config\Framework\SsiConfig($value['ssi']);
            unset($value['ssi']);
        }
    
        if (isset($value['fragments'])) {
            $this->fragments = new \Symfony\Config\Framework\FragmentsConfig($value['fragments']);
            unset($value['fragments']);
        }
    
        if (isset($value['profiler'])) {
            $this->profiler = new \Symfony\Config\Framework\ProfilerConfig($value['profiler']);
            unset($value['profiler']);
        }
    
        if (isset($value['workflows'])) {
            $this->workflows = new \Symfony\Config\Framework\WorkflowsConfig($value['workflows']);
            unset($value['workflows']);
        }
    
        if (isset($value['router'])) {
            $this->router = new \Symfony\Config\Framework\RouterConfig($value['router']);
            unset($value['router']);
        }
    
        if (isset($value['session'])) {
            $this->session = new \Symfony\Config\Framework\SessionConfig($value['session']);
            unset($value['session']);
        }
    
        if (isset($value['request'])) {
            $this->request = new \Symfony\Config\Framework\RequestConfig($value['request']);
            unset($value['request']);
        }
    
        if (isset($value['assets'])) {
            $this->assets = new \Symfony\Config\Framework\AssetsConfig($value['assets']);
            unset($value['assets']);
        }
    
        if (isset($value['translator'])) {
            $this->translator = new \Symfony\Config\Framework\TranslatorConfig($value['translator']);
            unset($value['translator']);
        }
    
        if (isset($value['validation'])) {
            $this->validation = new \Symfony\Config\Framework\ValidationConfig($value['validation']);
            unset($value['validation']);
        }
    
        if (isset($value['annotations'])) {
            $this->annotations = new \Symfony\Config\Framework\AnnotationsConfig($value['annotations']);
            unset($value['annotations']);
        }
    
        if (isset($value['serializer'])) {
            $this->serializer = new \Symfony\Config\Framework\SerializerConfig($value['serializer']);
            unset($value['serializer']);
        }
    
        if (isset($value['property_access'])) {
            $this->propertyAccess = new \Symfony\Config\Framework\PropertyAccessConfig($value['property_access']);
            unset($value['property_access']);
        }
    
        if (isset($value['property_info'])) {
            $this->propertyInfo = new \Symfony\Config\Framework\PropertyInfoConfig($value['property_info']);
            unset($value['property_info']);
        }
    
        if (isset($value['cache'])) {
            $this->cache = new \Symfony\Config\Framework\CacheConfig($value['cache']);
            unset($value['cache']);
        }
    
        if (isset($value['php_errors'])) {
            $this->phpErrors = new \Symfony\Config\Framework\PhpErrorsConfig($value['php_errors']);
            unset($value['php_errors']);
        }
    
        if (isset($value['web_link'])) {
            $this->webLink = new \Symfony\Config\Framework\WebLinkConfig($value['web_link']);
            unset($value['web_link']);
        }
    
        if (isset($value['lock'])) {
            $this->lock = new \Symfony\Config\Framework\LockConfig($value['lock']);
            unset($value['lock']);
        }
    
        if (isset($value['messenger'])) {
            $this->messenger = new \Symfony\Config\Framework\MessengerConfig($value['messenger']);
            unset($value['messenger']);
        }
    
        if (isset($value['disallow_search_engine_index'])) {
            $this->disallowSearchEngineIndex = $value['disallow_search_engine_index'];
            unset($value['disallow_search_engine_index']);
        }
    
        if (isset($value['http_client'])) {
            $this->httpClient = new \Symfony\Config\Framework\HttpClientConfig($value['http_client']);
            unset($value['http_client']);
        }
    
        if (isset($value['mailer'])) {
            $this->mailer = new \Symfony\Config\Framework\MailerConfig($value['mailer']);
            unset($value['mailer']);
        }
    
        if (isset($value['secrets'])) {
            $this->secrets = new \Symfony\Config\Framework\SecretsConfig($value['secrets']);
            unset($value['secrets']);
        }
    
        if (isset($value['notifier'])) {
            $this->notifier = new \Symfony\Config\Framework\NotifierConfig($value['notifier']);
            unset($value['notifier']);
        }
    
        if (isset($value['rate_limiter'])) {
            $this->rateLimiter = new \Symfony\Config\Framework\RateLimiterConfig($value['rate_limiter']);
            unset($value['rate_limiter']);
        }
    
        if (isset($value['uid'])) {
            $this->uid = new \Symfony\Config\Framework\UidConfig($value['uid']);
            unset($value['uid']);
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
        if (null !== $this->httpMethodOverride) {
            $output['http_method_override'] = $this->httpMethodOverride;
        }
        if (null !== $this->ide) {
            $output['ide'] = $this->ide;
        }
        if (null !== $this->test) {
            $output['test'] = $this->test;
        }
        if (null !== $this->defaultLocale) {
            $output['default_locale'] = $this->defaultLocale;
        }
        if (null !== $this->trustedHosts) {
            $output['trusted_hosts'] = $this->trustedHosts;
        }
        if (null !== $this->trustedProxies) {
            $output['trusted_proxies'] = $this->trustedProxies;
        }
        if (null !== $this->trustedHeaders) {
            $output['trusted_headers'] = $this->trustedHeaders;
        }
        if (null !== $this->errorController) {
            $output['error_controller'] = $this->errorController;
        }
        if (null !== $this->csrfProtection) {
            $output['csrf_protection'] = $this->csrfProtection->toArray();
        }
        if (null !== $this->form) {
            $output['form'] = $this->form->toArray();
        }
        if (null !== $this->httpCache) {
            $output['http_cache'] = $this->httpCache->toArray();
        }
        if (null !== $this->esi) {
            $output['esi'] = $this->esi->toArray();
        }
        if (null !== $this->ssi) {
            $output['ssi'] = $this->ssi->toArray();
        }
        if (null !== $this->fragments) {
            $output['fragments'] = $this->fragments->toArray();
        }
        if (null !== $this->profiler) {
            $output['profiler'] = $this->profiler->toArray();
        }
        if (null !== $this->workflows) {
            $output['workflows'] = $this->workflows->toArray();
        }
        if (null !== $this->router) {
            $output['router'] = $this->router->toArray();
        }
        if (null !== $this->session) {
            $output['session'] = $this->session->toArray();
        }
        if (null !== $this->request) {
            $output['request'] = $this->request->toArray();
        }
        if (null !== $this->assets) {
            $output['assets'] = $this->assets->toArray();
        }
        if (null !== $this->translator) {
            $output['translator'] = $this->translator->toArray();
        }
        if (null !== $this->validation) {
            $output['validation'] = $this->validation->toArray();
        }
        if (null !== $this->annotations) {
            $output['annotations'] = $this->annotations->toArray();
        }
        if (null !== $this->serializer) {
            $output['serializer'] = $this->serializer->toArray();
        }
        if (null !== $this->propertyAccess) {
            $output['property_access'] = $this->propertyAccess->toArray();
        }
        if (null !== $this->propertyInfo) {
            $output['property_info'] = $this->propertyInfo->toArray();
        }
        if (null !== $this->cache) {
            $output['cache'] = $this->cache->toArray();
        }
        if (null !== $this->phpErrors) {
            $output['php_errors'] = $this->phpErrors->toArray();
        }
        if (null !== $this->webLink) {
            $output['web_link'] = $this->webLink->toArray();
        }
        if (null !== $this->lock) {
            $output['lock'] = $this->lock->toArray();
        }
        if (null !== $this->messenger) {
            $output['messenger'] = $this->messenger->toArray();
        }
        if (null !== $this->disallowSearchEngineIndex) {
            $output['disallow_search_engine_index'] = $this->disallowSearchEngineIndex;
        }
        if (null !== $this->httpClient) {
            $output['http_client'] = $this->httpClient->toArray();
        }
        if (null !== $this->mailer) {
            $output['mailer'] = $this->mailer->toArray();
        }
        if (null !== $this->secrets) {
            $output['secrets'] = $this->secrets->toArray();
        }
        if (null !== $this->notifier) {
            $output['notifier'] = $this->notifier->toArray();
        }
        if (null !== $this->rateLimiter) {
            $output['rate_limiter'] = $this->rateLimiter->toArray();
        }
        if (null !== $this->uid) {
            $output['uid'] = $this->uid->toArray();
        }
    
        return $output;
    }
    

}
