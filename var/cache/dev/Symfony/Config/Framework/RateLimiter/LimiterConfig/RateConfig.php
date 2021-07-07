<?php

namespace Symfony\Config\Framework\RateLimiter\LimiterConfig;


use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class RateConfig 
{
    private $interval;
    private $amount;
    
    /**
     * Configures the rate interval. The value must be a number followed by "second", "minute", "hour", "day", "week" or "month" (or their plural equivalent).
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function interval($value): self
    {
        $this->interval = $value;
    
        return $this;
    }
    
    /**
     * Amount of tokens to add each interval
     * @default 1
     * @param ParamConfigurator|int $value
     * @return $this
     */
    public function amount($value): self
    {
        $this->amount = $value;
    
        return $this;
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['interval'])) {
            $this->interval = $value['interval'];
            unset($value['interval']);
        }
    
        if (isset($value['amount'])) {
            $this->amount = $value['amount'];
            unset($value['amount']);
        }
    
        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }
    
    
    public function toArray(): array
    {
        $output = [];
        if (null !== $this->interval) {
            $output['interval'] = $this->interval;
        }
        if (null !== $this->amount) {
            $output['amount'] = $this->amount;
        }
    
        return $output;
    }
    

}
