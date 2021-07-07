<?php

namespace Symfony\Config\Monolog;

require_once __DIR__.\DIRECTORY_SEPARATOR.'HandlerConfig'.\DIRECTORY_SEPARATOR.'ExcludedHttpCodeConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'HandlerConfig'.\DIRECTORY_SEPARATOR.'PublisherConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'HandlerConfig'.\DIRECTORY_SEPARATOR.'MongoConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'HandlerConfig'.\DIRECTORY_SEPARATOR.'ElasticsearchConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'HandlerConfig'.\DIRECTORY_SEPARATOR.'RedisConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'HandlerConfig'.\DIRECTORY_SEPARATOR.'PredisConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'HandlerConfig'.\DIRECTORY_SEPARATOR.'EmailPrototypeConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'HandlerConfig'.\DIRECTORY_SEPARATOR.'VerbosityLevelsConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'HandlerConfig'.\DIRECTORY_SEPARATOR.'ChannelsConfig.php';

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class HandlerConfig 
{
    private $type;
    private $id;
    private $priority;
    private $level;
    private $bubble;
    private $appName;
    private $includeStacktraces;
    private $processPsr3Messages;
    private $path;
    private $filePermission;
    private $useLocking;
    private $filenameFormat;
    private $dateFormat;
    private $ident;
    private $logopts;
    private $facility;
    private $maxFiles;
    private $actionLevel;
    private $activationStrategy;
    private $stopBuffering;
    private $passthruLevel;
    private $excluded404s;
    private $excludedHttpCodes;
    private $acceptedLevels;
    private $minLevel;
    private $maxLevel;
    private $bufferSize;
    private $flushOnOverflow;
    private $handler;
    private $url;
    private $exchange;
    private $exchangeName;
    private $room;
    private $messageFormat;
    private $apiVersion;
    private $channel;
    private $botName;
    private $useAttachment;
    private $useShortAttachment;
    private $includeExtra;
    private $iconEmoji;
    private $webhookUrl;
    private $team;
    private $notify;
    private $nickname;
    private $token;
    private $region;
    private $source;
    private $useSsl;
    private $user;
    private $title;
    private $host;
    private $port;
    private $publisher;
    private $mongo;
    private $elasticsearch;
    private $index;
    private $documentType;
    private $ignoreError;
    private $redis;
    private $predis;
    private $config;
    private $members;
    private $fromEmail;
    private $toEmail;
    private $subject;
    private $contentType;
    private $headers;
    private $mailer;
    private $emailPrototype;
    private $lazy;
    private $connectionString;
    private $timeout;
    private $time;
    private $deduplicationLevel;
    private $store;
    private $connectionTimeout;
    private $persistent;
    private $dsn;
    private $hubId;
    private $clientId;
    private $autoLogStacks;
    private $release;
    private $environment;
    private $messageType;
    private $tags;
    private $consoleFormaterOptions;
    private $consoleFormatterOptions;
    private $verbosityLevels;
    private $channels;
    private $formatter;
    private $nested;
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function type($value): self
    {
        $this->type = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function id($value): self
    {
        $this->id = $value;
    
        return $this;
    }
    
    /**
     * @default 0
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function priority($value): self
    {
        $this->priority = $value;
    
        return $this;
    }
    
    /**
     * @default 'DEBUG'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function level($value): self
    {
        $this->level = $value;
    
        return $this;
    }
    
    /**
     * @default true
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function bubble($value): self
    {
        $this->bubble = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function appName($value): self
    {
        $this->appName = $value;
    
        return $this;
    }
    
    /**
     * @default false
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function includeStacktraces($value): self
    {
        $this->includeStacktraces = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function processPsr3Messages($value): self
    {
        $this->processPsr3Messages = $value;
    
        return $this;
    }
    
    /**
     * @default '%kernel.logs_dir%/%kernel.environment%.log'
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
    public function filePermission($value): self
    {
        $this->filePermission = $value;
    
        return $this;
    }
    
    /**
     * @default false
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function useLocking($value): self
    {
        $this->useLocking = $value;
    
        return $this;
    }
    
    /**
     * @default '{filename}-{date}'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function filenameFormat($value): self
    {
        $this->filenameFormat = $value;
    
        return $this;
    }
    
    /**
     * @default 'Y-m-d'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function dateFormat($value): self
    {
        $this->dateFormat = $value;
    
        return $this;
    }
    
    /**
     * @default false
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function ident($value): self
    {
        $this->ident = $value;
    
        return $this;
    }
    
    /**
     * @default 1
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function logopts($value): self
    {
        $this->logopts = $value;
    
        return $this;
    }
    
    /**
     * @default 'user'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function facility($value): self
    {
        $this->facility = $value;
    
        return $this;
    }
    
    /**
     * @default 0
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function maxFiles($value): self
    {
        $this->maxFiles = $value;
    
        return $this;
    }
    
    /**
     * @default 'WARNING'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function actionLevel($value): self
    {
        $this->actionLevel = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function activationStrategy($value): self
    {
        $this->activationStrategy = $value;
    
        return $this;
    }
    
    /**
     * @default true
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function stopBuffering($value): self
    {
        $this->stopBuffering = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function passthruLevel($value): self
    {
        $this->passthruLevel = $value;
    
        return $this;
    }
    
    /**
     * @param ParamConfigurator|list<mixed|ParamConfigurator> $value
     * @return $this
     */
    public function excluded404s($value): self
    {
        $this->excluded404s = $value;
    
        return $this;
    }
    
    public function excludedHttpCode(array $value = []): \Symfony\Config\Monolog\HandlerConfig\ExcludedHttpCodeConfig
    {
        return $this->excludedHttpCodes[] = new \Symfony\Config\Monolog\HandlerConfig\ExcludedHttpCodeConfig($value);
    }
    
    /**
     * @param ParamConfigurator|list<mixed|ParamConfigurator> $value
     * @return $this
     */
    public function acceptedLevels($value): self
    {
        $this->acceptedLevels = $value;
    
        return $this;
    }
    
    /**
     * @default 'DEBUG'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function minLevel($value): self
    {
        $this->minLevel = $value;
    
        return $this;
    }
    
    /**
     * @default 'EMERGENCY'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function maxLevel($value): self
    {
        $this->maxLevel = $value;
    
        return $this;
    }
    
    /**
     * @default 0
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function bufferSize($value): self
    {
        $this->bufferSize = $value;
    
        return $this;
    }
    
    /**
     * @default false
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function flushOnOverflow($value): self
    {
        $this->flushOnOverflow = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function handler($value): self
    {
        $this->handler = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function url($value): self
    {
        $this->url = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function exchange($value): self
    {
        $this->exchange = $value;
    
        return $this;
    }
    
    /**
     * @default 'log'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function exchangeName($value): self
    {
        $this->exchangeName = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function room($value): self
    {
        $this->room = $value;
    
        return $this;
    }
    
    /**
     * @default 'text'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function messageFormat($value): self
    {
        $this->messageFormat = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function apiVersion($value): self
    {
        $this->apiVersion = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function channel($value): self
    {
        $this->channel = $value;
    
        return $this;
    }
    
    /**
     * @default 'Monolog'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function botName($value): self
    {
        $this->botName = $value;
    
        return $this;
    }
    
    /**
     * @default true
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function useAttachment($value): self
    {
        $this->useAttachment = $value;
    
        return $this;
    }
    
    /**
     * @default false
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function useShortAttachment($value): self
    {
        $this->useShortAttachment = $value;
    
        return $this;
    }
    
    /**
     * @default false
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function includeExtra($value): self
    {
        $this->includeExtra = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function iconEmoji($value): self
    {
        $this->iconEmoji = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function webhookUrl($value): self
    {
        $this->webhookUrl = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function team($value): self
    {
        $this->team = $value;
    
        return $this;
    }
    
    /**
     * @default false
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function notify($value): self
    {
        $this->notify = $value;
    
        return $this;
    }
    
    /**
     * @default 'Monolog'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function nickname($value): self
    {
        $this->nickname = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function token($value): self
    {
        $this->token = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function region($value): self
    {
        $this->region = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function source($value): self
    {
        $this->source = $value;
    
        return $this;
    }
    
    /**
     * @default true
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function useSsl($value): self
    {
        $this->useSsl = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function user($value): self
    {
        $this->user = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function title($value): self
    {
        $this->title = $value;
    
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
     * @default 514
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function port($value): self
    {
        $this->port = $value;
    
        return $this;
    }
    
    public function publisher(array $value = []): \Symfony\Config\Monolog\HandlerConfig\PublisherConfig
    {
        if (null === $this->publisher) {
            $this->publisher = new \Symfony\Config\Monolog\HandlerConfig\PublisherConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "publisher()" has already been initialized. You cannot pass values the second time you call publisher().');
        }
    
        return $this->publisher;
    }
    
    public function mongo(array $value = []): \Symfony\Config\Monolog\HandlerConfig\MongoConfig
    {
        if (null === $this->mongo) {
            $this->mongo = new \Symfony\Config\Monolog\HandlerConfig\MongoConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "mongo()" has already been initialized. You cannot pass values the second time you call mongo().');
        }
    
        return $this->mongo;
    }
    
    public function elasticsearch(array $value = []): \Symfony\Config\Monolog\HandlerConfig\ElasticsearchConfig
    {
        if (null === $this->elasticsearch) {
            $this->elasticsearch = new \Symfony\Config\Monolog\HandlerConfig\ElasticsearchConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "elasticsearch()" has already been initialized. You cannot pass values the second time you call elasticsearch().');
        }
    
        return $this->elasticsearch;
    }
    
    /**
     * @default 'monolog'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function index($value): self
    {
        $this->index = $value;
    
        return $this;
    }
    
    /**
     * @default 'logs'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function documentType($value): self
    {
        $this->documentType = $value;
    
        return $this;
    }
    
    /**
     * @default false
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function ignoreError($value): self
    {
        $this->ignoreError = $value;
    
        return $this;
    }
    
    public function redis(array $value = []): \Symfony\Config\Monolog\HandlerConfig\RedisConfig
    {
        if (null === $this->redis) {
            $this->redis = new \Symfony\Config\Monolog\HandlerConfig\RedisConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "redis()" has already been initialized. You cannot pass values the second time you call redis().');
        }
    
        return $this->redis;
    }
    
    public function predis(array $value = []): \Symfony\Config\Monolog\HandlerConfig\PredisConfig
    {
        if (null === $this->predis) {
            $this->predis = new \Symfony\Config\Monolog\HandlerConfig\PredisConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "predis()" has already been initialized. You cannot pass values the second time you call predis().');
        }
    
        return $this->predis;
    }
    
    /**
     * @param ParamConfigurator|list<mixed|ParamConfigurator> $value
     * @return $this
     */
    public function config($value): self
    {
        $this->config = $value;
    
        return $this;
    }
    
    /**
     * @param ParamConfigurator|list<mixed|ParamConfigurator> $value
     * @return $this
     */
    public function members($value): self
    {
        $this->members = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function fromEmail($value): self
    {
        $this->fromEmail = $value;
    
        return $this;
    }
    
    /**
     * @param ParamConfigurator|list<mixed|ParamConfigurator> $value
     * @return $this
     */
    public function toEmail($value): self
    {
        $this->toEmail = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function subject($value): self
    {
        $this->subject = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function contentType($value): self
    {
        $this->contentType = $value;
    
        return $this;
    }
    
    /**
     * @param ParamConfigurator|list<mixed|ParamConfigurator> $value
     * @return $this
     */
    public function headers($value): self
    {
        $this->headers = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function mailer($value): self
    {
        $this->mailer = $value;
    
        return $this;
    }
    
    public function emailPrototype(array $value = []): \Symfony\Config\Monolog\HandlerConfig\EmailPrototypeConfig
    {
        if (null === $this->emailPrototype) {
            $this->emailPrototype = new \Symfony\Config\Monolog\HandlerConfig\EmailPrototypeConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "emailPrototype()" has already been initialized. You cannot pass values the second time you call emailPrototype().');
        }
    
        return $this->emailPrototype;
    }
    
    /**
     * @default true
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
    public function connectionString($value): self
    {
        $this->connectionString = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function timeout($value): self
    {
        $this->timeout = $value;
    
        return $this;
    }
    
    /**
     * @default 60
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function time($value): self
    {
        $this->time = $value;
    
        return $this;
    }
    
    /**
     * @default 400
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function deduplicationLevel($value): self
    {
        $this->deduplicationLevel = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function store($value): self
    {
        $this->store = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function connectionTimeout($value): self
    {
        $this->connectionTimeout = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function persistent($value): self
    {
        $this->persistent = $value;
    
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
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function hubId($value): self
    {
        $this->hubId = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function clientId($value): self
    {
        $this->clientId = $value;
    
        return $this;
    }
    
    /**
     * @default false
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function autoLogStacks($value): self
    {
        $this->autoLogStacks = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function release($value): self
    {
        $this->release = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function environment($value): self
    {
        $this->environment = $value;
    
        return $this;
    }
    
    /**
     * @default 0
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function messageType($value): self
    {
        $this->messageType = $value;
    
        return $this;
    }
    
    /**
     * @param ParamConfigurator|list<mixed|ParamConfigurator> $value
     * @return $this
     */
    public function tags($value): self
    {
        $this->tags = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @deprecated ".console_formater_options" is deprecated, use ".console_formatter_options" instead.
     * @return $this
     */
    public function consoleFormaterOptions($value): self
    {
        $this->consoleFormaterOptions = $value;
    
        return $this;
    }
    
    /**
     * @default array (
    )
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function consoleFormatterOptions($value = array (
    )): self
    {
        $this->consoleFormatterOptions = $value;
    
        return $this;
    }
    
    public function verbosityLevels(array $value = []): \Symfony\Config\Monolog\HandlerConfig\VerbosityLevelsConfig
    {
        if (null === $this->verbosityLevels) {
            $this->verbosityLevels = new \Symfony\Config\Monolog\HandlerConfig\VerbosityLevelsConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "verbosityLevels()" has already been initialized. You cannot pass values the second time you call verbosityLevels().');
        }
    
        return $this->verbosityLevels;
    }
    
    public function channels(array $value = []): \Symfony\Config\Monolog\HandlerConfig\ChannelsConfig
    {
        if (null === $this->channels) {
            $this->channels = new \Symfony\Config\Monolog\HandlerConfig\ChannelsConfig($value);
        } elseif ([] !== $value) {
            throw new InvalidConfigurationException('The node created by "channels()" has already been initialized. You cannot pass values the second time you call channels().');
        }
    
        return $this->channels;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function formatter($value): self
    {
        $this->formatter = $value;
    
        return $this;
    }
    
    /**
     * @default false
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function nested($value): self
    {
        $this->nested = $value;
    
        return $this;
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['type'])) {
            $this->type = $value['type'];
            unset($value['type']);
        }
    
        if (isset($value['id'])) {
            $this->id = $value['id'];
            unset($value['id']);
        }
    
        if (isset($value['priority'])) {
            $this->priority = $value['priority'];
            unset($value['priority']);
        }
    
        if (isset($value['level'])) {
            $this->level = $value['level'];
            unset($value['level']);
        }
    
        if (isset($value['bubble'])) {
            $this->bubble = $value['bubble'];
            unset($value['bubble']);
        }
    
        if (isset($value['app_name'])) {
            $this->appName = $value['app_name'];
            unset($value['app_name']);
        }
    
        if (isset($value['include_stacktraces'])) {
            $this->includeStacktraces = $value['include_stacktraces'];
            unset($value['include_stacktraces']);
        }
    
        if (isset($value['process_psr_3_messages'])) {
            $this->processPsr3Messages = $value['process_psr_3_messages'];
            unset($value['process_psr_3_messages']);
        }
    
        if (isset($value['path'])) {
            $this->path = $value['path'];
            unset($value['path']);
        }
    
        if (isset($value['file_permission'])) {
            $this->filePermission = $value['file_permission'];
            unset($value['file_permission']);
        }
    
        if (isset($value['use_locking'])) {
            $this->useLocking = $value['use_locking'];
            unset($value['use_locking']);
        }
    
        if (isset($value['filename_format'])) {
            $this->filenameFormat = $value['filename_format'];
            unset($value['filename_format']);
        }
    
        if (isset($value['date_format'])) {
            $this->dateFormat = $value['date_format'];
            unset($value['date_format']);
        }
    
        if (isset($value['ident'])) {
            $this->ident = $value['ident'];
            unset($value['ident']);
        }
    
        if (isset($value['logopts'])) {
            $this->logopts = $value['logopts'];
            unset($value['logopts']);
        }
    
        if (isset($value['facility'])) {
            $this->facility = $value['facility'];
            unset($value['facility']);
        }
    
        if (isset($value['max_files'])) {
            $this->maxFiles = $value['max_files'];
            unset($value['max_files']);
        }
    
        if (isset($value['action_level'])) {
            $this->actionLevel = $value['action_level'];
            unset($value['action_level']);
        }
    
        if (isset($value['activation_strategy'])) {
            $this->activationStrategy = $value['activation_strategy'];
            unset($value['activation_strategy']);
        }
    
        if (isset($value['stop_buffering'])) {
            $this->stopBuffering = $value['stop_buffering'];
            unset($value['stop_buffering']);
        }
    
        if (isset($value['passthru_level'])) {
            $this->passthruLevel = $value['passthru_level'];
            unset($value['passthru_level']);
        }
    
        if (isset($value['excluded_404s'])) {
            $this->excluded404s = $value['excluded_404s'];
            unset($value['excluded_404s']);
        }
    
        if (isset($value['excluded_http_codes'])) {
            $this->excludedHttpCodes = array_map(function ($v) { return new \Symfony\Config\Monolog\HandlerConfig\ExcludedHttpCodeConfig($v); }, $value['excluded_http_codes']);
            unset($value['excluded_http_codes']);
        }
    
        if (isset($value['accepted_levels'])) {
            $this->acceptedLevels = $value['accepted_levels'];
            unset($value['accepted_levels']);
        }
    
        if (isset($value['min_level'])) {
            $this->minLevel = $value['min_level'];
            unset($value['min_level']);
        }
    
        if (isset($value['max_level'])) {
            $this->maxLevel = $value['max_level'];
            unset($value['max_level']);
        }
    
        if (isset($value['buffer_size'])) {
            $this->bufferSize = $value['buffer_size'];
            unset($value['buffer_size']);
        }
    
        if (isset($value['flush_on_overflow'])) {
            $this->flushOnOverflow = $value['flush_on_overflow'];
            unset($value['flush_on_overflow']);
        }
    
        if (isset($value['handler'])) {
            $this->handler = $value['handler'];
            unset($value['handler']);
        }
    
        if (isset($value['url'])) {
            $this->url = $value['url'];
            unset($value['url']);
        }
    
        if (isset($value['exchange'])) {
            $this->exchange = $value['exchange'];
            unset($value['exchange']);
        }
    
        if (isset($value['exchange_name'])) {
            $this->exchangeName = $value['exchange_name'];
            unset($value['exchange_name']);
        }
    
        if (isset($value['room'])) {
            $this->room = $value['room'];
            unset($value['room']);
        }
    
        if (isset($value['message_format'])) {
            $this->messageFormat = $value['message_format'];
            unset($value['message_format']);
        }
    
        if (isset($value['api_version'])) {
            $this->apiVersion = $value['api_version'];
            unset($value['api_version']);
        }
    
        if (isset($value['channel'])) {
            $this->channel = $value['channel'];
            unset($value['channel']);
        }
    
        if (isset($value['bot_name'])) {
            $this->botName = $value['bot_name'];
            unset($value['bot_name']);
        }
    
        if (isset($value['use_attachment'])) {
            $this->useAttachment = $value['use_attachment'];
            unset($value['use_attachment']);
        }
    
        if (isset($value['use_short_attachment'])) {
            $this->useShortAttachment = $value['use_short_attachment'];
            unset($value['use_short_attachment']);
        }
    
        if (isset($value['include_extra'])) {
            $this->includeExtra = $value['include_extra'];
            unset($value['include_extra']);
        }
    
        if (isset($value['icon_emoji'])) {
            $this->iconEmoji = $value['icon_emoji'];
            unset($value['icon_emoji']);
        }
    
        if (isset($value['webhook_url'])) {
            $this->webhookUrl = $value['webhook_url'];
            unset($value['webhook_url']);
        }
    
        if (isset($value['team'])) {
            $this->team = $value['team'];
            unset($value['team']);
        }
    
        if (isset($value['notify'])) {
            $this->notify = $value['notify'];
            unset($value['notify']);
        }
    
        if (isset($value['nickname'])) {
            $this->nickname = $value['nickname'];
            unset($value['nickname']);
        }
    
        if (isset($value['token'])) {
            $this->token = $value['token'];
            unset($value['token']);
        }
    
        if (isset($value['region'])) {
            $this->region = $value['region'];
            unset($value['region']);
        }
    
        if (isset($value['source'])) {
            $this->source = $value['source'];
            unset($value['source']);
        }
    
        if (isset($value['use_ssl'])) {
            $this->useSsl = $value['use_ssl'];
            unset($value['use_ssl']);
        }
    
        if (isset($value['user'])) {
            $this->user = $value['user'];
            unset($value['user']);
        }
    
        if (isset($value['title'])) {
            $this->title = $value['title'];
            unset($value['title']);
        }
    
        if (isset($value['host'])) {
            $this->host = $value['host'];
            unset($value['host']);
        }
    
        if (isset($value['port'])) {
            $this->port = $value['port'];
            unset($value['port']);
        }
    
        if (isset($value['publisher'])) {
            $this->publisher = new \Symfony\Config\Monolog\HandlerConfig\PublisherConfig($value['publisher']);
            unset($value['publisher']);
        }
    
        if (isset($value['mongo'])) {
            $this->mongo = new \Symfony\Config\Monolog\HandlerConfig\MongoConfig($value['mongo']);
            unset($value['mongo']);
        }
    
        if (isset($value['elasticsearch'])) {
            $this->elasticsearch = new \Symfony\Config\Monolog\HandlerConfig\ElasticsearchConfig($value['elasticsearch']);
            unset($value['elasticsearch']);
        }
    
        if (isset($value['index'])) {
            $this->index = $value['index'];
            unset($value['index']);
        }
    
        if (isset($value['document_type'])) {
            $this->documentType = $value['document_type'];
            unset($value['document_type']);
        }
    
        if (isset($value['ignore_error'])) {
            $this->ignoreError = $value['ignore_error'];
            unset($value['ignore_error']);
        }
    
        if (isset($value['redis'])) {
            $this->redis = new \Symfony\Config\Monolog\HandlerConfig\RedisConfig($value['redis']);
            unset($value['redis']);
        }
    
        if (isset($value['predis'])) {
            $this->predis = new \Symfony\Config\Monolog\HandlerConfig\PredisConfig($value['predis']);
            unset($value['predis']);
        }
    
        if (isset($value['config'])) {
            $this->config = $value['config'];
            unset($value['config']);
        }
    
        if (isset($value['members'])) {
            $this->members = $value['members'];
            unset($value['members']);
        }
    
        if (isset($value['from_email'])) {
            $this->fromEmail = $value['from_email'];
            unset($value['from_email']);
        }
    
        if (isset($value['to_email'])) {
            $this->toEmail = $value['to_email'];
            unset($value['to_email']);
        }
    
        if (isset($value['subject'])) {
            $this->subject = $value['subject'];
            unset($value['subject']);
        }
    
        if (isset($value['content_type'])) {
            $this->contentType = $value['content_type'];
            unset($value['content_type']);
        }
    
        if (isset($value['headers'])) {
            $this->headers = $value['headers'];
            unset($value['headers']);
        }
    
        if (isset($value['mailer'])) {
            $this->mailer = $value['mailer'];
            unset($value['mailer']);
        }
    
        if (isset($value['email_prototype'])) {
            $this->emailPrototype = new \Symfony\Config\Monolog\HandlerConfig\EmailPrototypeConfig($value['email_prototype']);
            unset($value['email_prototype']);
        }
    
        if (isset($value['lazy'])) {
            $this->lazy = $value['lazy'];
            unset($value['lazy']);
        }
    
        if (isset($value['connection_string'])) {
            $this->connectionString = $value['connection_string'];
            unset($value['connection_string']);
        }
    
        if (isset($value['timeout'])) {
            $this->timeout = $value['timeout'];
            unset($value['timeout']);
        }
    
        if (isset($value['time'])) {
            $this->time = $value['time'];
            unset($value['time']);
        }
    
        if (isset($value['deduplication_level'])) {
            $this->deduplicationLevel = $value['deduplication_level'];
            unset($value['deduplication_level']);
        }
    
        if (isset($value['store'])) {
            $this->store = $value['store'];
            unset($value['store']);
        }
    
        if (isset($value['connection_timeout'])) {
            $this->connectionTimeout = $value['connection_timeout'];
            unset($value['connection_timeout']);
        }
    
        if (isset($value['persistent'])) {
            $this->persistent = $value['persistent'];
            unset($value['persistent']);
        }
    
        if (isset($value['dsn'])) {
            $this->dsn = $value['dsn'];
            unset($value['dsn']);
        }
    
        if (isset($value['hub_id'])) {
            $this->hubId = $value['hub_id'];
            unset($value['hub_id']);
        }
    
        if (isset($value['client_id'])) {
            $this->clientId = $value['client_id'];
            unset($value['client_id']);
        }
    
        if (isset($value['auto_log_stacks'])) {
            $this->autoLogStacks = $value['auto_log_stacks'];
            unset($value['auto_log_stacks']);
        }
    
        if (isset($value['release'])) {
            $this->release = $value['release'];
            unset($value['release']);
        }
    
        if (isset($value['environment'])) {
            $this->environment = $value['environment'];
            unset($value['environment']);
        }
    
        if (isset($value['message_type'])) {
            $this->messageType = $value['message_type'];
            unset($value['message_type']);
        }
    
        if (isset($value['tags'])) {
            $this->tags = $value['tags'];
            unset($value['tags']);
        }
    
        if (isset($value['console_formater_options'])) {
            $this->consoleFormaterOptions = $value['console_formater_options'];
            unset($value['console_formater_options']);
        }
    
        if (isset($value['console_formatter_options'])) {
            $this->consoleFormatterOptions = $value['console_formatter_options'];
            unset($value['console_formatter_options']);
        }
    
        if (isset($value['verbosity_levels'])) {
            $this->verbosityLevels = new \Symfony\Config\Monolog\HandlerConfig\VerbosityLevelsConfig($value['verbosity_levels']);
            unset($value['verbosity_levels']);
        }
    
        if (isset($value['channels'])) {
            $this->channels = new \Symfony\Config\Monolog\HandlerConfig\ChannelsConfig($value['channels']);
            unset($value['channels']);
        }
    
        if (isset($value['formatter'])) {
            $this->formatter = $value['formatter'];
            unset($value['formatter']);
        }
    
        if (isset($value['nested'])) {
            $this->nested = $value['nested'];
            unset($value['nested']);
        }
    
        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }
    
    
    public function toArray(): array
    {
        $output = [];
        if (null !== $this->type) {
            $output['type'] = $this->type;
        }
        if (null !== $this->id) {
            $output['id'] = $this->id;
        }
        if (null !== $this->priority) {
            $output['priority'] = $this->priority;
        }
        if (null !== $this->level) {
            $output['level'] = $this->level;
        }
        if (null !== $this->bubble) {
            $output['bubble'] = $this->bubble;
        }
        if (null !== $this->appName) {
            $output['app_name'] = $this->appName;
        }
        if (null !== $this->includeStacktraces) {
            $output['include_stacktraces'] = $this->includeStacktraces;
        }
        if (null !== $this->processPsr3Messages) {
            $output['process_psr_3_messages'] = $this->processPsr3Messages;
        }
        if (null !== $this->path) {
            $output['path'] = $this->path;
        }
        if (null !== $this->filePermission) {
            $output['file_permission'] = $this->filePermission;
        }
        if (null !== $this->useLocking) {
            $output['use_locking'] = $this->useLocking;
        }
        if (null !== $this->filenameFormat) {
            $output['filename_format'] = $this->filenameFormat;
        }
        if (null !== $this->dateFormat) {
            $output['date_format'] = $this->dateFormat;
        }
        if (null !== $this->ident) {
            $output['ident'] = $this->ident;
        }
        if (null !== $this->logopts) {
            $output['logopts'] = $this->logopts;
        }
        if (null !== $this->facility) {
            $output['facility'] = $this->facility;
        }
        if (null !== $this->maxFiles) {
            $output['max_files'] = $this->maxFiles;
        }
        if (null !== $this->actionLevel) {
            $output['action_level'] = $this->actionLevel;
        }
        if (null !== $this->activationStrategy) {
            $output['activation_strategy'] = $this->activationStrategy;
        }
        if (null !== $this->stopBuffering) {
            $output['stop_buffering'] = $this->stopBuffering;
        }
        if (null !== $this->passthruLevel) {
            $output['passthru_level'] = $this->passthruLevel;
        }
        if (null !== $this->excluded404s) {
            $output['excluded_404s'] = $this->excluded404s;
        }
        if (null !== $this->excludedHttpCodes) {
            $output['excluded_http_codes'] = array_map(function ($v) { return $v->toArray(); }, $this->excludedHttpCodes);
        }
        if (null !== $this->acceptedLevels) {
            $output['accepted_levels'] = $this->acceptedLevels;
        }
        if (null !== $this->minLevel) {
            $output['min_level'] = $this->minLevel;
        }
        if (null !== $this->maxLevel) {
            $output['max_level'] = $this->maxLevel;
        }
        if (null !== $this->bufferSize) {
            $output['buffer_size'] = $this->bufferSize;
        }
        if (null !== $this->flushOnOverflow) {
            $output['flush_on_overflow'] = $this->flushOnOverflow;
        }
        if (null !== $this->handler) {
            $output['handler'] = $this->handler;
        }
        if (null !== $this->url) {
            $output['url'] = $this->url;
        }
        if (null !== $this->exchange) {
            $output['exchange'] = $this->exchange;
        }
        if (null !== $this->exchangeName) {
            $output['exchange_name'] = $this->exchangeName;
        }
        if (null !== $this->room) {
            $output['room'] = $this->room;
        }
        if (null !== $this->messageFormat) {
            $output['message_format'] = $this->messageFormat;
        }
        if (null !== $this->apiVersion) {
            $output['api_version'] = $this->apiVersion;
        }
        if (null !== $this->channel) {
            $output['channel'] = $this->channel;
        }
        if (null !== $this->botName) {
            $output['bot_name'] = $this->botName;
        }
        if (null !== $this->useAttachment) {
            $output['use_attachment'] = $this->useAttachment;
        }
        if (null !== $this->useShortAttachment) {
            $output['use_short_attachment'] = $this->useShortAttachment;
        }
        if (null !== $this->includeExtra) {
            $output['include_extra'] = $this->includeExtra;
        }
        if (null !== $this->iconEmoji) {
            $output['icon_emoji'] = $this->iconEmoji;
        }
        if (null !== $this->webhookUrl) {
            $output['webhook_url'] = $this->webhookUrl;
        }
        if (null !== $this->team) {
            $output['team'] = $this->team;
        }
        if (null !== $this->notify) {
            $output['notify'] = $this->notify;
        }
        if (null !== $this->nickname) {
            $output['nickname'] = $this->nickname;
        }
        if (null !== $this->token) {
            $output['token'] = $this->token;
        }
        if (null !== $this->region) {
            $output['region'] = $this->region;
        }
        if (null !== $this->source) {
            $output['source'] = $this->source;
        }
        if (null !== $this->useSsl) {
            $output['use_ssl'] = $this->useSsl;
        }
        if (null !== $this->user) {
            $output['user'] = $this->user;
        }
        if (null !== $this->title) {
            $output['title'] = $this->title;
        }
        if (null !== $this->host) {
            $output['host'] = $this->host;
        }
        if (null !== $this->port) {
            $output['port'] = $this->port;
        }
        if (null !== $this->publisher) {
            $output['publisher'] = $this->publisher->toArray();
        }
        if (null !== $this->mongo) {
            $output['mongo'] = $this->mongo->toArray();
        }
        if (null !== $this->elasticsearch) {
            $output['elasticsearch'] = $this->elasticsearch->toArray();
        }
        if (null !== $this->index) {
            $output['index'] = $this->index;
        }
        if (null !== $this->documentType) {
            $output['document_type'] = $this->documentType;
        }
        if (null !== $this->ignoreError) {
            $output['ignore_error'] = $this->ignoreError;
        }
        if (null !== $this->redis) {
            $output['redis'] = $this->redis->toArray();
        }
        if (null !== $this->predis) {
            $output['predis'] = $this->predis->toArray();
        }
        if (null !== $this->config) {
            $output['config'] = $this->config;
        }
        if (null !== $this->members) {
            $output['members'] = $this->members;
        }
        if (null !== $this->fromEmail) {
            $output['from_email'] = $this->fromEmail;
        }
        if (null !== $this->toEmail) {
            $output['to_email'] = $this->toEmail;
        }
        if (null !== $this->subject) {
            $output['subject'] = $this->subject;
        }
        if (null !== $this->contentType) {
            $output['content_type'] = $this->contentType;
        }
        if (null !== $this->headers) {
            $output['headers'] = $this->headers;
        }
        if (null !== $this->mailer) {
            $output['mailer'] = $this->mailer;
        }
        if (null !== $this->emailPrototype) {
            $output['email_prototype'] = $this->emailPrototype->toArray();
        }
        if (null !== $this->lazy) {
            $output['lazy'] = $this->lazy;
        }
        if (null !== $this->connectionString) {
            $output['connection_string'] = $this->connectionString;
        }
        if (null !== $this->timeout) {
            $output['timeout'] = $this->timeout;
        }
        if (null !== $this->time) {
            $output['time'] = $this->time;
        }
        if (null !== $this->deduplicationLevel) {
            $output['deduplication_level'] = $this->deduplicationLevel;
        }
        if (null !== $this->store) {
            $output['store'] = $this->store;
        }
        if (null !== $this->connectionTimeout) {
            $output['connection_timeout'] = $this->connectionTimeout;
        }
        if (null !== $this->persistent) {
            $output['persistent'] = $this->persistent;
        }
        if (null !== $this->dsn) {
            $output['dsn'] = $this->dsn;
        }
        if (null !== $this->hubId) {
            $output['hub_id'] = $this->hubId;
        }
        if (null !== $this->clientId) {
            $output['client_id'] = $this->clientId;
        }
        if (null !== $this->autoLogStacks) {
            $output['auto_log_stacks'] = $this->autoLogStacks;
        }
        if (null !== $this->release) {
            $output['release'] = $this->release;
        }
        if (null !== $this->environment) {
            $output['environment'] = $this->environment;
        }
        if (null !== $this->messageType) {
            $output['message_type'] = $this->messageType;
        }
        if (null !== $this->tags) {
            $output['tags'] = $this->tags;
        }
        if (null !== $this->consoleFormaterOptions) {
            $output['console_formater_options'] = $this->consoleFormaterOptions;
        }
        if (null !== $this->consoleFormatterOptions) {
            $output['console_formatter_options'] = $this->consoleFormatterOptions;
        }
        if (null !== $this->verbosityLevels) {
            $output['verbosity_levels'] = $this->verbosityLevels->toArray();
        }
        if (null !== $this->channels) {
            $output['channels'] = $this->channels->toArray();
        }
        if (null !== $this->formatter) {
            $output['formatter'] = $this->formatter;
        }
        if (null !== $this->nested) {
            $output['nested'] = $this->nested;
        }
    
        return $output;
    }
    

}
