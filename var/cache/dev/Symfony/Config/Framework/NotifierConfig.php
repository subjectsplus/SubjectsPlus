<?php

namespace Symfony\Config\Framework;

require_once __DIR__.\DIRECTORY_SEPARATOR.'Notifier'.\DIRECTORY_SEPARATOR.'AdminRecipientConfig.php';

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class NotifierConfig 
{
    private $enabled;
    private $chatterTransports;
    private $texterTransports;
    private $notificationOnFailedMessages;
    private $channelPolicy;
    private $adminRecipients;
    
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
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function chatterTransport(string $name, $value): self
    {
        $this->chatterTransports[$name] = $value;
    
        return $this;
    }
    
    /**
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function texterTransport(string $name, $value): self
    {
        $this->texterTransports[$name] = $value;
    
        return $this;
    }
    
    /**
     * @default false
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function notificationOnFailedMessages($value): self
    {
        $this->notificationOnFailedMessages = $value;
    
        return $this;
    }
    
    /**
     * @param ParamConfigurator|array $value
     * @return $this
     */
    public function channelPolicy(string $name, $value): self
    {
        $this->channelPolicy[$name] = $value;
    
        return $this;
    }
    
    public function adminRecipient(array $value = []): \Symfony\Config\Framework\Notifier\AdminRecipientConfig
    {
        return $this->adminRecipients[] = new \Symfony\Config\Framework\Notifier\AdminRecipientConfig($value);
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['enabled'])) {
            $this->enabled = $value['enabled'];
            unset($value['enabled']);
        }
    
        if (isset($value['chatter_transports'])) {
            $this->chatterTransports = $value['chatter_transports'];
            unset($value['chatter_transports']);
        }
    
        if (isset($value['texter_transports'])) {
            $this->texterTransports = $value['texter_transports'];
            unset($value['texter_transports']);
        }
    
        if (isset($value['notification_on_failed_messages'])) {
            $this->notificationOnFailedMessages = $value['notification_on_failed_messages'];
            unset($value['notification_on_failed_messages']);
        }
    
        if (isset($value['channel_policy'])) {
            $this->channelPolicy = $value['channel_policy'];
            unset($value['channel_policy']);
        }
    
        if (isset($value['admin_recipients'])) {
            $this->adminRecipients = array_map(function ($v) { return new \Symfony\Config\Framework\Notifier\AdminRecipientConfig($v); }, $value['admin_recipients']);
            unset($value['admin_recipients']);
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
        if (null !== $this->chatterTransports) {
            $output['chatter_transports'] = $this->chatterTransports;
        }
        if (null !== $this->texterTransports) {
            $output['texter_transports'] = $this->texterTransports;
        }
        if (null !== $this->notificationOnFailedMessages) {
            $output['notification_on_failed_messages'] = $this->notificationOnFailedMessages;
        }
        if (null !== $this->channelPolicy) {
            $output['channel_policy'] = $this->channelPolicy;
        }
        if (null !== $this->adminRecipients) {
            $output['admin_recipients'] = array_map(function ($v) { return $v->toArray(); }, $this->adminRecipients);
        }
    
        return $output;
    }
    

}
