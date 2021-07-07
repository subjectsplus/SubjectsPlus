<?php

namespace Symfony\Config\Framework\Mailer;


use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class EnvelopeConfig 
{
    private $sender;
    private $recipients;
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function sender($value): self
    {
        $this->sender = $value;
    
        return $this;
    }
    
    /**
     * @param ParamConfigurator|list<mixed|ParamConfigurator> $value
     * @return $this
     */
    public function recipients($value): self
    {
        $this->recipients = $value;
    
        return $this;
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['sender'])) {
            $this->sender = $value['sender'];
            unset($value['sender']);
        }
    
        if (isset($value['recipients'])) {
            $this->recipients = $value['recipients'];
            unset($value['recipients']);
        }
    
        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }
    
    
    public function toArray(): array
    {
        $output = [];
        if (null !== $this->sender) {
            $output['sender'] = $this->sender;
        }
        if (null !== $this->recipients) {
            $output['recipients'] = $this->recipients;
        }
    
        return $output;
    }
    

}
