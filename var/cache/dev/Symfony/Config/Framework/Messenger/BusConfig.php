<?php

namespace Symfony\Config\Framework\Messenger;

require_once __DIR__.\DIRECTORY_SEPARATOR.'BusConfig'.\DIRECTORY_SEPARATOR.'MiddlewareConfig.php';

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class BusConfig 
{
    private $defaultMiddleware;
    private $middleware;
    
    /**
     * @default true
     * @param ParamConfigurator|true|false|'allow_no_handlers' $value
     * @return $this
     */
    public function defaultMiddleware($value): self
    {
        $this->defaultMiddleware = $value;
    
        return $this;
    }
    
    public function middleware(array $value = []): \Symfony\Config\Framework\Messenger\BusConfig\MiddlewareConfig
    {
        return $this->middleware[] = new \Symfony\Config\Framework\Messenger\BusConfig\MiddlewareConfig($value);
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['default_middleware'])) {
            $this->defaultMiddleware = $value['default_middleware'];
            unset($value['default_middleware']);
        }
    
        if (isset($value['middleware'])) {
            $this->middleware = array_map(function ($v) { return new \Symfony\Config\Framework\Messenger\BusConfig\MiddlewareConfig($v); }, $value['middleware']);
            unset($value['middleware']);
        }
    
        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }
    
    
    public function toArray(): array
    {
        $output = [];
        if (null !== $this->defaultMiddleware) {
            $output['default_middleware'] = $this->defaultMiddleware;
        }
        if (null !== $this->middleware) {
            $output['middleware'] = array_map(function ($v) { return $v->toArray(); }, $this->middleware);
        }
    
        return $output;
    }
    

}
