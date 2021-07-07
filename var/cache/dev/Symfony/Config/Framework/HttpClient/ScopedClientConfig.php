<?php

namespace Symfony\Config\Framework\HttpClient;

require_once __DIR__.\DIRECTORY_SEPARATOR.'ScopedClientConfig'.\DIRECTORY_SEPARATOR.'PeerFingerprintConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'ScopedClientConfig'.\DIRECTORY_SEPARATOR.'RetryFailedConfig.php';

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class ScopedClientConfig 
{
    private $scope;
    private $baseUri;
    private $authBasic;
    private $authBearer;
    private $authNtlm;
    private $query;
    private $headers;
    private $maxRedirects;
    private $httpVersion;
    private $resolve;
    private $proxy;
    private $noProxy;
    private $timeout;
    private $maxDuration;
    private $bindto;
    private $verifyPeer;
    private $verifyHost;
    private $cafile;
    private $capath;
    private $localCert;
    private $localPk;
    private $passphrase;
    private $ciphers;
    private $peerFingerprint;
    private $retryFailed;
    
    /**
     * The regular expression that the request URL must match before adding the other options. When none is provided, the base URI is used instead.
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function scope($value): self
    {
        $this->scope = $value;
    
        return $this;
    }
    
    /**
     * The URI to resolve relative URLs, following rules in RFC 3985, section 2.
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function baseUri($value): self
    {
        $this->baseUri = $value;
    
        return $this;
    }
    
    /**
     * An HTTP Basic authentication "username:password".
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function authBasic($value): self
    {
        $this->authBasic = $value;
    
        return $this;
    }
    
    /**
     * A token enabling HTTP Bearer authorization.
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function authBearer($value): self
    {
        $this->authBearer = $value;
    
        return $this;
    }
    
    /**
     * A "username:password" pair to use Microsoft NTLM authentication (requires the cURL extension).
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function authNtlm($value): self
    {
        $this->authNtlm = $value;
    
        return $this;
    }
    
    /**
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function query(string $key, $value): self
    {
        $this->query[$key] = $value;
    
        return $this;
    }
    
    /**
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function header(string $name, $value): self
    {
        $this->headers[$name] = $value;
    
        return $this;
    }
    
    /**
     * The maximum number of redirects to follow.
     * @default null
     * @param ParamConfigurator|int $value
     * @return $this
     */
    public function maxRedirects($value): self
    {
        $this->maxRedirects = $value;
    
        return $this;
    }
    
    /**
     * The default HTTP version, typically 1.1 or 2.0, leave to null for the best version.
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function httpVersion($value): self
    {
        $this->httpVersion = $value;
    
        return $this;
    }
    
    /**
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function resolve(string $host, $value): self
    {
        $this->resolve[$host] = $value;
    
        return $this;
    }
    
    /**
     * The URL of the proxy to pass requests through or null for automatic detection.
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function proxy($value): self
    {
        $this->proxy = $value;
    
        return $this;
    }
    
    /**
     * A comma separated list of hosts that do not require a proxy to be reached.
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function noProxy($value): self
    {
        $this->noProxy = $value;
    
        return $this;
    }
    
    /**
     * The idle timeout, defaults to the "default_socket_timeout" ini parameter.
     * @default null
     * @param ParamConfigurator|float $value
     * @return $this
     */
    public function timeout($value): self
    {
        $this->timeout = $value;
    
        return $this;
    }
    
    /**
     * The maximum execution time for the request+response as a whole.
     * @default null
     * @param ParamConfigurator|float $value
     * @return $this
     */
    public function maxDuration($value): self
    {
        $this->maxDuration = $value;
    
        return $this;
    }
    
    /**
     * A network interface name, IP address, a host name or a UNIX socket to bind to.
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function bindto($value): self
    {
        $this->bindto = $value;
    
        return $this;
    }
    
    /**
     * Indicates if the peer should be verified in an SSL/TLS context.
     * @default null
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function verifyPeer($value): self
    {
        $this->verifyPeer = $value;
    
        return $this;
    }
    
    /**
     * Indicates if the host should exist as a certificate common name.
     * @default null
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function verifyHost($value): self
    {
        $this->verifyHost = $value;
    
        return $this;
    }
    
    /**
     * A certificate authority file.
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function cafile($value): self
    {
        $this->cafile = $value;
    
        return $this;
    }
    
    /**
     * A directory that contains multiple certificate authority files.
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function capath($value): self
    {
        $this->capath = $value;
    
        return $this;
    }
    
    /**
     * A PEM formatted certificate file.
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function localCert($value): self
    {
        $this->localCert = $value;
    
        return $this;
    }
    
    /**
     * A private key file.
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function localPk($value): self
    {
        $this->localPk = $value;
    
        return $this;
    }
    
    /**
     * The passphrase used to encrypt the "local_pk" file.
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function passphrase($value): self
    {
        $this->passphrase = $value;
    
        return $this;
    }
    
    /**
     * A list of SSL/TLS ciphers separated by colons, commas or spaces (e.g. "RC3-SHA:TLS13-AES-128-GCM-SHA256"...)
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function ciphers($value): self
    {
        $this->ciphers = $value;
    
        return $this;
    }
    
    public function peerFingerprint(array $value = []): \Symfony\Config\Framework\HttpClient\ScopedClientConfig\PeerFingerprintConfig
    {
        if (null === $this->peerFingerprint) {
            $this->peerFingerprint = new \Symfony\Config\Framework\HttpClient\ScopedClientConfig\PeerFingerprintConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "peerFingerprint()" has already been initialized. You cannot pass values the second time you call peerFingerprint().');
        }
    
        return $this->peerFingerprint;
    }
    
    public function retryFailed(array $value = []): \Symfony\Config\Framework\HttpClient\ScopedClientConfig\RetryFailedConfig
    {
        if (null === $this->retryFailed) {
            $this->retryFailed = new \Symfony\Config\Framework\HttpClient\ScopedClientConfig\RetryFailedConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "retryFailed()" has already been initialized. You cannot pass values the second time you call retryFailed().');
        }
    
        return $this->retryFailed;
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['scope'])) {
            $this->scope = $value['scope'];
            unset($value['scope']);
        }
    
        if (isset($value['base_uri'])) {
            $this->baseUri = $value['base_uri'];
            unset($value['base_uri']);
        }
    
        if (isset($value['auth_basic'])) {
            $this->authBasic = $value['auth_basic'];
            unset($value['auth_basic']);
        }
    
        if (isset($value['auth_bearer'])) {
            $this->authBearer = $value['auth_bearer'];
            unset($value['auth_bearer']);
        }
    
        if (isset($value['auth_ntlm'])) {
            $this->authNtlm = $value['auth_ntlm'];
            unset($value['auth_ntlm']);
        }
    
        if (isset($value['query'])) {
            $this->query = $value['query'];
            unset($value['query']);
        }
    
        if (isset($value['headers'])) {
            $this->headers = $value['headers'];
            unset($value['headers']);
        }
    
        if (isset($value['max_redirects'])) {
            $this->maxRedirects = $value['max_redirects'];
            unset($value['max_redirects']);
        }
    
        if (isset($value['http_version'])) {
            $this->httpVersion = $value['http_version'];
            unset($value['http_version']);
        }
    
        if (isset($value['resolve'])) {
            $this->resolve = $value['resolve'];
            unset($value['resolve']);
        }
    
        if (isset($value['proxy'])) {
            $this->proxy = $value['proxy'];
            unset($value['proxy']);
        }
    
        if (isset($value['no_proxy'])) {
            $this->noProxy = $value['no_proxy'];
            unset($value['no_proxy']);
        }
    
        if (isset($value['timeout'])) {
            $this->timeout = $value['timeout'];
            unset($value['timeout']);
        }
    
        if (isset($value['max_duration'])) {
            $this->maxDuration = $value['max_duration'];
            unset($value['max_duration']);
        }
    
        if (isset($value['bindto'])) {
            $this->bindto = $value['bindto'];
            unset($value['bindto']);
        }
    
        if (isset($value['verify_peer'])) {
            $this->verifyPeer = $value['verify_peer'];
            unset($value['verify_peer']);
        }
    
        if (isset($value['verify_host'])) {
            $this->verifyHost = $value['verify_host'];
            unset($value['verify_host']);
        }
    
        if (isset($value['cafile'])) {
            $this->cafile = $value['cafile'];
            unset($value['cafile']);
        }
    
        if (isset($value['capath'])) {
            $this->capath = $value['capath'];
            unset($value['capath']);
        }
    
        if (isset($value['local_cert'])) {
            $this->localCert = $value['local_cert'];
            unset($value['local_cert']);
        }
    
        if (isset($value['local_pk'])) {
            $this->localPk = $value['local_pk'];
            unset($value['local_pk']);
        }
    
        if (isset($value['passphrase'])) {
            $this->passphrase = $value['passphrase'];
            unset($value['passphrase']);
        }
    
        if (isset($value['ciphers'])) {
            $this->ciphers = $value['ciphers'];
            unset($value['ciphers']);
        }
    
        if (isset($value['peer_fingerprint'])) {
            $this->peerFingerprint = new \Symfony\Config\Framework\HttpClient\ScopedClientConfig\PeerFingerprintConfig($value['peer_fingerprint']);
            unset($value['peer_fingerprint']);
        }
    
        if (isset($value['retry_failed'])) {
            $this->retryFailed = new \Symfony\Config\Framework\HttpClient\ScopedClientConfig\RetryFailedConfig($value['retry_failed']);
            unset($value['retry_failed']);
        }
    
        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }
    
    
    public function toArray(): array
    {
        $output = [];
        if (null !== $this->scope) {
            $output['scope'] = $this->scope;
        }
        if (null !== $this->baseUri) {
            $output['base_uri'] = $this->baseUri;
        }
        if (null !== $this->authBasic) {
            $output['auth_basic'] = $this->authBasic;
        }
        if (null !== $this->authBearer) {
            $output['auth_bearer'] = $this->authBearer;
        }
        if (null !== $this->authNtlm) {
            $output['auth_ntlm'] = $this->authNtlm;
        }
        if (null !== $this->query) {
            $output['query'] = $this->query;
        }
        if (null !== $this->headers) {
            $output['headers'] = $this->headers;
        }
        if (null !== $this->maxRedirects) {
            $output['max_redirects'] = $this->maxRedirects;
        }
        if (null !== $this->httpVersion) {
            $output['http_version'] = $this->httpVersion;
        }
        if (null !== $this->resolve) {
            $output['resolve'] = $this->resolve;
        }
        if (null !== $this->proxy) {
            $output['proxy'] = $this->proxy;
        }
        if (null !== $this->noProxy) {
            $output['no_proxy'] = $this->noProxy;
        }
        if (null !== $this->timeout) {
            $output['timeout'] = $this->timeout;
        }
        if (null !== $this->maxDuration) {
            $output['max_duration'] = $this->maxDuration;
        }
        if (null !== $this->bindto) {
            $output['bindto'] = $this->bindto;
        }
        if (null !== $this->verifyPeer) {
            $output['verify_peer'] = $this->verifyPeer;
        }
        if (null !== $this->verifyHost) {
            $output['verify_host'] = $this->verifyHost;
        }
        if (null !== $this->cafile) {
            $output['cafile'] = $this->cafile;
        }
        if (null !== $this->capath) {
            $output['capath'] = $this->capath;
        }
        if (null !== $this->localCert) {
            $output['local_cert'] = $this->localCert;
        }
        if (null !== $this->localPk) {
            $output['local_pk'] = $this->localPk;
        }
        if (null !== $this->passphrase) {
            $output['passphrase'] = $this->passphrase;
        }
        if (null !== $this->ciphers) {
            $output['ciphers'] = $this->ciphers;
        }
        if (null !== $this->peerFingerprint) {
            $output['peer_fingerprint'] = $this->peerFingerprint->toArray();
        }
        if (null !== $this->retryFailed) {
            $output['retry_failed'] = $this->retryFailed->toArray();
        }
    
        return $output;
    }
    

}
