<?php

namespace Symfony\Config\Framework;

require_once __DIR__.\DIRECTORY_SEPARATOR.'Mailer'.\DIRECTORY_SEPARATOR.'EnvelopeConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'Mailer'.\DIRECTORY_SEPARATOR.'HeaderConfig.php';

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class MailerConfig 
{
    private $enabled;
    private $messageBus;
    private $dsn;
    private $transports;
    private $envelope;
    private $headers;
    
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
     * The message bus to use. Defaults to the default bus if the Messenger component is installed.
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function messageBus($value): self
    {
        $this->messageBus = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function dsn($value): self
    {
        $this->dsn = $value;
    
        return $this;
    }
    
    /**
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function transport(string $name, $value): self
    {
        $this->transports[$name] = $value;
    
        return $this;
    }
    
    public function envelope(array $value = []): \Symfony\Config\Framework\Mailer\EnvelopeConfig
    {
        if (null === $this->envelope) {
            $this->envelope = new \Symfony\Config\Framework\Mailer\EnvelopeConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "envelope()" has already been initialized. You cannot pass values the second time you call envelope().');
        }
    
        return $this->envelope;
    }
    
    public function header(string $name, array $value = []): \Symfony\Config\Framework\Mailer\HeaderConfig
    {
        if (!isset($this->headers[$name])) {
            return $this->headers[$name] = new \Symfony\Config\Framework\Mailer\HeaderConfig($value);
        }
        if ([] === $value) {
            return $this->headers[$name];
        }
    
        throw new InvalidConfigurationException('The node created by "header()" has already been initialized. You cannot pass values the second time you call header().');
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['enabled'])) {
            $this->enabled = $value['enabled'];
            unset($value['enabled']);
        }
    
        if (isset($value['message_bus'])) {
            $this->messageBus = $value['message_bus'];
            unset($value['message_bus']);
        }
    
        if (isset($value['dsn'])) {
            $this->dsn = $value['dsn'];
            unset($value['dsn']);
        }
    
        if (isset($value['transports'])) {
            $this->transports = $value['transports'];
            unset($value['transports']);
        }
    
        if (isset($value['envelope'])) {
            $this->envelope = new \Symfony\Config\Framework\Mailer\EnvelopeConfig($value['envelope']);
            unset($value['envelope']);
        }
    
        if (isset($value['headers'])) {
            $this->headers = array_map(function ($v) { return new \Symfony\Config\Framework\Mailer\HeaderConfig($v); }, $value['headers']);
            unset($value['headers']);
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
        if (null !== $this->messageBus) {
            $output['message_bus'] = $this->messageBus;
        }
        if (null !== $this->dsn) {
            $output['dsn'] = $this->dsn;
        }
        if (null !== $this->transports) {
            $output['transports'] = $this->transports;
        }
        if (null !== $this->envelope) {
            $output['envelope'] = $this->envelope->toArray();
        }
        if (null !== $this->headers) {
            $output['headers'] = array_map(function ($v) { return $v->toArray(); }, $this->headers);
        }
    
        return $output;
    }
    

}
