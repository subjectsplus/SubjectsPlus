<?php

namespace Symfony\Config\Doctrine\Dbal;

require_once __DIR__.\DIRECTORY_SEPARATOR.'ConnectionConfig'.\DIRECTORY_SEPARATOR.'SlaveConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'ConnectionConfig'.\DIRECTORY_SEPARATOR.'ReplicaConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'ConnectionConfig'.\DIRECTORY_SEPARATOR.'ShardConfig.php';

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class ConnectionConfig 
{
    private $url;
    private $dbname;
    private $host;
    private $port;
    private $user;
    private $password;
    private $overrideUrl;
    private $dbnameSuffix;
    private $applicationName;
    private $charset;
    private $path;
    private $memory;
    private $unixSocket;
    private $persistent;
    private $protocol;
    private $service;
    private $servicename;
    private $sessionMode;
    private $server;
    private $defaultDbname;
    private $sslmode;
    private $sslrootcert;
    private $sslcert;
    private $sslkey;
    private $sslcrl;
    private $pooled;
    private $multipleActiveResultSets;
    private $useSavepoints;
    private $instancename;
    private $connectstring;
    private $driver;
    private $platformService;
    private $autoCommit;
    private $schemaFilter;
    private $logging;
    private $profiling;
    private $profilingCollectBacktrace;
    private $profilingCollectSchemaErrors;
    private $serverVersion;
    private $driverClass;
    private $wrapperClass;
    private $shardManagerClass;
    private $shardChoser;
    private $shardChoserService;
    private $keepSlave;
    private $keepReplica;
    private $options;
    private $mappingTypes;
    private $defaultTableOptions;
    private $slaves;
    private $replicas;
    private $shards;
    
    /**
     * A URL with connection information; any parameter value parsed from this string will override explicitly set parameters
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
    public function dbname($value): self
    {
        $this->dbname = $value;
    
        return $this;
    }
    
    /**
     * Defaults to "localhost" at runtime.
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
     * Defaults to null at runtime.
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function port($value): self
    {
        $this->port = $value;
    
        return $this;
    }
    
    /**
     * Defaults to "root" at runtime.
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
     * Defaults to null at runtime.
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function password($value): self
    {
        $this->password = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|bool $value
     * @deprecated The "doctrine.dbal.override_url" configuration key is deprecated.
     * @return $this
     */
    public function overrideUrl($value): self
    {
        $this->overrideUrl = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function dbnameSuffix($value): self
    {
        $this->dbnameSuffix = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function applicationName($value): self
    {
        $this->applicationName = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function charset($value): self
    {
        $this->charset = $value;
    
        return $this;
    }
    
    /**
     * @default null
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
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function memory($value): self
    {
        $this->memory = $value;
    
        return $this;
    }
    
    /**
     * The unix socket to use for MySQL
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function unixSocket($value): self
    {
        $this->unixSocket = $value;
    
        return $this;
    }
    
    /**
     * True to use as persistent connection for the ibm_db2 driver
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
     * The protocol to use for the ibm_db2 driver (default to TCPIP if omitted)
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function protocol($value): self
    {
        $this->protocol = $value;
    
        return $this;
    }
    
    /**
     * True to use SERVICE_NAME as connection parameter instead of SID for Oracle
     * @default null
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function service($value): self
    {
        $this->service = $value;
    
        return $this;
    }
    
    /**
     * Overrules dbname parameter if given and used as SERVICE_NAME or SID connection parameter for Oracle depending on the service parameter.
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function servicename($value): self
    {
        $this->servicename = $value;
    
        return $this;
    }
    
    /**
     * The session mode to use for the oci8 driver
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function sessionMode($value): self
    {
        $this->sessionMode = $value;
    
        return $this;
    }
    
    /**
     * The name of a running database server to connect to for SQL Anywhere.
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function server($value): self
    {
        $this->server = $value;
    
        return $this;
    }
    
    /**
     * Override the default database (postgres) to connect to for PostgreSQL connexion.
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function defaultDbname($value): self
    {
        $this->defaultDbname = $value;
    
        return $this;
    }
    
    /**
     * Determines whether or with what priority a SSL TCP/IP connection will be negotiated with the server for PostgreSQL.
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function sslmode($value): self
    {
        $this->sslmode = $value;
    
        return $this;
    }
    
    /**
     * The name of a file containing SSL certificate authority (CA) certificate(s). If the file exists, the server's certificate will be verified to be signed by one of these authorities.
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function sslrootcert($value): self
    {
        $this->sslrootcert = $value;
    
        return $this;
    }
    
    /**
     * The path to the SSL client certificate file for PostgreSQL.
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function sslcert($value): self
    {
        $this->sslcert = $value;
    
        return $this;
    }
    
    /**
     * The path to the SSL client key file for PostgreSQL.
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function sslkey($value): self
    {
        $this->sslkey = $value;
    
        return $this;
    }
    
    /**
     * The file name of the SSL certificate revocation list for PostgreSQL.
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function sslcrl($value): self
    {
        $this->sslcrl = $value;
    
        return $this;
    }
    
    /**
     * True to use a pooled server with the oci8/pdo_oracle driver
     * @default null
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function pooled($value): self
    {
        $this->pooled = $value;
    
        return $this;
    }
    
    /**
     * Configuring MultipleActiveResultSets for the pdo_sqlsrv driver
     * @default null
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function multipleActiveResultSets($value): self
    {
        $this->multipleActiveResultSets = $value;
    
        return $this;
    }
    
    /**
     * Use savepoints for nested transactions
     * @default null
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function useSavepoints($value): self
    {
        $this->useSavepoints = $value;
    
        return $this;
    }
    
    /**
     * Optional parameter, complete whether to add the INSTANCE_NAME parameter in the connection. It is generally used to connect to an Oracle RAC server to select the name of a particular instance.
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function instancename($value): self
    {
        $this->instancename = $value;
    
        return $this;
    }
    
    /**
     * Complete Easy Connect connection descriptor, see https://docs.oracle.com/database/121/NETAG/naming.htm.When using this option, you will still need to provide the user and password parameters, but the other parameters will no longer be used. Note that when using this parameter, the getHost and getPort methods from Doctrine\DBAL\Connection will no longer function as expected.
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function connectstring($value): self
    {
        $this->connectstring = $value;
    
        return $this;
    }
    
    /**
     * @default 'pdo_mysql'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function driver($value): self
    {
        $this->driver = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function platformService($value): self
    {
        $this->platformService = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function autoCommit($value): self
    {
        $this->autoCommit = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function schemaFilter($value): self
    {
        $this->schemaFilter = $value;
    
        return $this;
    }
    
    /**
     * @default true
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function logging($value): self
    {
        $this->logging = $value;
    
        return $this;
    }
    
    /**
     * @default true
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function profiling($value): self
    {
        $this->profiling = $value;
    
        return $this;
    }
    
    /**
     * Enables collecting backtraces when profiling is enabled
     * @default false
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function profilingCollectBacktrace($value): self
    {
        $this->profilingCollectBacktrace = $value;
    
        return $this;
    }
    
    /**
     * Enables collecting schema errors when profiling is enabled
     * @default true
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function profilingCollectSchemaErrors($value): self
    {
        $this->profilingCollectSchemaErrors = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function serverVersion($value): self
    {
        $this->serverVersion = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function driverClass($value): self
    {
        $this->driverClass = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function wrapperClass($value): self
    {
        $this->wrapperClass = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function shardManagerClass($value): self
    {
        $this->shardManagerClass = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function shardChoser($value): self
    {
        $this->shardChoser = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function shardChoserService($value): self
    {
        $this->shardChoserService = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|bool $value
     * @deprecated The "keep_slave" configuration key is deprecated since doctrine-bundle 2.2. Use the "keep_replica" configuration key instead.
     * @return $this
     */
    public function keepSlave($value): self
    {
        $this->keepSlave = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function keepReplica($value): self
    {
        $this->keepReplica = $value;
    
        return $this;
    }
    
    /**
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function option(string $key, $value): self
    {
        $this->options[$key] = $value;
    
        return $this;
    }
    
    /**
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function mappingType(string $name, $value): self
    {
        $this->mappingTypes[$name] = $value;
    
        return $this;
    }
    
    /**
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function defaultTableOption(string $name, $value): self
    {
        $this->defaultTableOptions[$name] = $value;
    
        return $this;
    }
    
    public function slave(string $name, array $value = []): \Symfony\Config\Doctrine\Dbal\ConnectionConfig\SlaveConfig
    {
        if (!isset($this->slaves[$name])) {
            return $this->slaves[$name] = new \Symfony\Config\Doctrine\Dbal\ConnectionConfig\SlaveConfig($value);
        }
        if ([] === $value) {
            return $this->slaves[$name];
        }
    
        throw new InvalidConfigurationException('The node created by "slave()" has already been initialized. You cannot pass values the second time you call slave().');
    }
    
    public function replica(string $name, array $value = []): \Symfony\Config\Doctrine\Dbal\ConnectionConfig\ReplicaConfig
    {
        if (!isset($this->replicas[$name])) {
            return $this->replicas[$name] = new \Symfony\Config\Doctrine\Dbal\ConnectionConfig\ReplicaConfig($value);
        }
        if ([] === $value) {
            return $this->replicas[$name];
        }
    
        throw new InvalidConfigurationException('The node created by "replica()" has already been initialized. You cannot pass values the second time you call replica().');
    }
    
    public function shard(array $value = []): \Symfony\Config\Doctrine\Dbal\ConnectionConfig\ShardConfig
    {
        return $this->shards[] = new \Symfony\Config\Doctrine\Dbal\ConnectionConfig\ShardConfig($value);
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['url'])) {
            $this->url = $value['url'];
            unset($value['url']);
        }
    
        if (isset($value['dbname'])) {
            $this->dbname = $value['dbname'];
            unset($value['dbname']);
        }
    
        if (isset($value['host'])) {
            $this->host = $value['host'];
            unset($value['host']);
        }
    
        if (isset($value['port'])) {
            $this->port = $value['port'];
            unset($value['port']);
        }
    
        if (isset($value['user'])) {
            $this->user = $value['user'];
            unset($value['user']);
        }
    
        if (isset($value['password'])) {
            $this->password = $value['password'];
            unset($value['password']);
        }
    
        if (isset($value['override_url'])) {
            $this->overrideUrl = $value['override_url'];
            unset($value['override_url']);
        }
    
        if (isset($value['dbname_suffix'])) {
            $this->dbnameSuffix = $value['dbname_suffix'];
            unset($value['dbname_suffix']);
        }
    
        if (isset($value['application_name'])) {
            $this->applicationName = $value['application_name'];
            unset($value['application_name']);
        }
    
        if (isset($value['charset'])) {
            $this->charset = $value['charset'];
            unset($value['charset']);
        }
    
        if (isset($value['path'])) {
            $this->path = $value['path'];
            unset($value['path']);
        }
    
        if (isset($value['memory'])) {
            $this->memory = $value['memory'];
            unset($value['memory']);
        }
    
        if (isset($value['unix_socket'])) {
            $this->unixSocket = $value['unix_socket'];
            unset($value['unix_socket']);
        }
    
        if (isset($value['persistent'])) {
            $this->persistent = $value['persistent'];
            unset($value['persistent']);
        }
    
        if (isset($value['protocol'])) {
            $this->protocol = $value['protocol'];
            unset($value['protocol']);
        }
    
        if (isset($value['service'])) {
            $this->service = $value['service'];
            unset($value['service']);
        }
    
        if (isset($value['servicename'])) {
            $this->servicename = $value['servicename'];
            unset($value['servicename']);
        }
    
        if (isset($value['sessionMode'])) {
            $this->sessionMode = $value['sessionMode'];
            unset($value['sessionMode']);
        }
    
        if (isset($value['server'])) {
            $this->server = $value['server'];
            unset($value['server']);
        }
    
        if (isset($value['default_dbname'])) {
            $this->defaultDbname = $value['default_dbname'];
            unset($value['default_dbname']);
        }
    
        if (isset($value['sslmode'])) {
            $this->sslmode = $value['sslmode'];
            unset($value['sslmode']);
        }
    
        if (isset($value['sslrootcert'])) {
            $this->sslrootcert = $value['sslrootcert'];
            unset($value['sslrootcert']);
        }
    
        if (isset($value['sslcert'])) {
            $this->sslcert = $value['sslcert'];
            unset($value['sslcert']);
        }
    
        if (isset($value['sslkey'])) {
            $this->sslkey = $value['sslkey'];
            unset($value['sslkey']);
        }
    
        if (isset($value['sslcrl'])) {
            $this->sslcrl = $value['sslcrl'];
            unset($value['sslcrl']);
        }
    
        if (isset($value['pooled'])) {
            $this->pooled = $value['pooled'];
            unset($value['pooled']);
        }
    
        if (isset($value['MultipleActiveResultSets'])) {
            $this->multipleActiveResultSets = $value['MultipleActiveResultSets'];
            unset($value['MultipleActiveResultSets']);
        }
    
        if (isset($value['use_savepoints'])) {
            $this->useSavepoints = $value['use_savepoints'];
            unset($value['use_savepoints']);
        }
    
        if (isset($value['instancename'])) {
            $this->instancename = $value['instancename'];
            unset($value['instancename']);
        }
    
        if (isset($value['connectstring'])) {
            $this->connectstring = $value['connectstring'];
            unset($value['connectstring']);
        }
    
        if (isset($value['driver'])) {
            $this->driver = $value['driver'];
            unset($value['driver']);
        }
    
        if (isset($value['platform_service'])) {
            $this->platformService = $value['platform_service'];
            unset($value['platform_service']);
        }
    
        if (isset($value['auto_commit'])) {
            $this->autoCommit = $value['auto_commit'];
            unset($value['auto_commit']);
        }
    
        if (isset($value['schema_filter'])) {
            $this->schemaFilter = $value['schema_filter'];
            unset($value['schema_filter']);
        }
    
        if (isset($value['logging'])) {
            $this->logging = $value['logging'];
            unset($value['logging']);
        }
    
        if (isset($value['profiling'])) {
            $this->profiling = $value['profiling'];
            unset($value['profiling']);
        }
    
        if (isset($value['profiling_collect_backtrace'])) {
            $this->profilingCollectBacktrace = $value['profiling_collect_backtrace'];
            unset($value['profiling_collect_backtrace']);
        }
    
        if (isset($value['profiling_collect_schema_errors'])) {
            $this->profilingCollectSchemaErrors = $value['profiling_collect_schema_errors'];
            unset($value['profiling_collect_schema_errors']);
        }
    
        if (isset($value['server_version'])) {
            $this->serverVersion = $value['server_version'];
            unset($value['server_version']);
        }
    
        if (isset($value['driver_class'])) {
            $this->driverClass = $value['driver_class'];
            unset($value['driver_class']);
        }
    
        if (isset($value['wrapper_class'])) {
            $this->wrapperClass = $value['wrapper_class'];
            unset($value['wrapper_class']);
        }
    
        if (isset($value['shard_manager_class'])) {
            $this->shardManagerClass = $value['shard_manager_class'];
            unset($value['shard_manager_class']);
        }
    
        if (isset($value['shard_choser'])) {
            $this->shardChoser = $value['shard_choser'];
            unset($value['shard_choser']);
        }
    
        if (isset($value['shard_choser_service'])) {
            $this->shardChoserService = $value['shard_choser_service'];
            unset($value['shard_choser_service']);
        }
    
        if (isset($value['keep_slave'])) {
            $this->keepSlave = $value['keep_slave'];
            unset($value['keep_slave']);
        }
    
        if (isset($value['keep_replica'])) {
            $this->keepReplica = $value['keep_replica'];
            unset($value['keep_replica']);
        }
    
        if (isset($value['options'])) {
            $this->options = $value['options'];
            unset($value['options']);
        }
    
        if (isset($value['mapping_types'])) {
            $this->mappingTypes = $value['mapping_types'];
            unset($value['mapping_types']);
        }
    
        if (isset($value['default_table_options'])) {
            $this->defaultTableOptions = $value['default_table_options'];
            unset($value['default_table_options']);
        }
    
        if (isset($value['slaves'])) {
            $this->slaves = array_map(function ($v) { return new \Symfony\Config\Doctrine\Dbal\ConnectionConfig\SlaveConfig($v); }, $value['slaves']);
            unset($value['slaves']);
        }
    
        if (isset($value['replicas'])) {
            $this->replicas = array_map(function ($v) { return new \Symfony\Config\Doctrine\Dbal\ConnectionConfig\ReplicaConfig($v); }, $value['replicas']);
            unset($value['replicas']);
        }
    
        if (isset($value['shards'])) {
            $this->shards = array_map(function ($v) { return new \Symfony\Config\Doctrine\Dbal\ConnectionConfig\ShardConfig($v); }, $value['shards']);
            unset($value['shards']);
        }
    
        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }
    
    
    public function toArray(): array
    {
        $output = [];
        if (null !== $this->url) {
            $output['url'] = $this->url;
        }
        if (null !== $this->dbname) {
            $output['dbname'] = $this->dbname;
        }
        if (null !== $this->host) {
            $output['host'] = $this->host;
        }
        if (null !== $this->port) {
            $output['port'] = $this->port;
        }
        if (null !== $this->user) {
            $output['user'] = $this->user;
        }
        if (null !== $this->password) {
            $output['password'] = $this->password;
        }
        if (null !== $this->overrideUrl) {
            $output['override_url'] = $this->overrideUrl;
        }
        if (null !== $this->dbnameSuffix) {
            $output['dbname_suffix'] = $this->dbnameSuffix;
        }
        if (null !== $this->applicationName) {
            $output['application_name'] = $this->applicationName;
        }
        if (null !== $this->charset) {
            $output['charset'] = $this->charset;
        }
        if (null !== $this->path) {
            $output['path'] = $this->path;
        }
        if (null !== $this->memory) {
            $output['memory'] = $this->memory;
        }
        if (null !== $this->unixSocket) {
            $output['unix_socket'] = $this->unixSocket;
        }
        if (null !== $this->persistent) {
            $output['persistent'] = $this->persistent;
        }
        if (null !== $this->protocol) {
            $output['protocol'] = $this->protocol;
        }
        if (null !== $this->service) {
            $output['service'] = $this->service;
        }
        if (null !== $this->servicename) {
            $output['servicename'] = $this->servicename;
        }
        if (null !== $this->sessionMode) {
            $output['sessionMode'] = $this->sessionMode;
        }
        if (null !== $this->server) {
            $output['server'] = $this->server;
        }
        if (null !== $this->defaultDbname) {
            $output['default_dbname'] = $this->defaultDbname;
        }
        if (null !== $this->sslmode) {
            $output['sslmode'] = $this->sslmode;
        }
        if (null !== $this->sslrootcert) {
            $output['sslrootcert'] = $this->sslrootcert;
        }
        if (null !== $this->sslcert) {
            $output['sslcert'] = $this->sslcert;
        }
        if (null !== $this->sslkey) {
            $output['sslkey'] = $this->sslkey;
        }
        if (null !== $this->sslcrl) {
            $output['sslcrl'] = $this->sslcrl;
        }
        if (null !== $this->pooled) {
            $output['pooled'] = $this->pooled;
        }
        if (null !== $this->multipleActiveResultSets) {
            $output['MultipleActiveResultSets'] = $this->multipleActiveResultSets;
        }
        if (null !== $this->useSavepoints) {
            $output['use_savepoints'] = $this->useSavepoints;
        }
        if (null !== $this->instancename) {
            $output['instancename'] = $this->instancename;
        }
        if (null !== $this->connectstring) {
            $output['connectstring'] = $this->connectstring;
        }
        if (null !== $this->driver) {
            $output['driver'] = $this->driver;
        }
        if (null !== $this->platformService) {
            $output['platform_service'] = $this->platformService;
        }
        if (null !== $this->autoCommit) {
            $output['auto_commit'] = $this->autoCommit;
        }
        if (null !== $this->schemaFilter) {
            $output['schema_filter'] = $this->schemaFilter;
        }
        if (null !== $this->logging) {
            $output['logging'] = $this->logging;
        }
        if (null !== $this->profiling) {
            $output['profiling'] = $this->profiling;
        }
        if (null !== $this->profilingCollectBacktrace) {
            $output['profiling_collect_backtrace'] = $this->profilingCollectBacktrace;
        }
        if (null !== $this->profilingCollectSchemaErrors) {
            $output['profiling_collect_schema_errors'] = $this->profilingCollectSchemaErrors;
        }
        if (null !== $this->serverVersion) {
            $output['server_version'] = $this->serverVersion;
        }
        if (null !== $this->driverClass) {
            $output['driver_class'] = $this->driverClass;
        }
        if (null !== $this->wrapperClass) {
            $output['wrapper_class'] = $this->wrapperClass;
        }
        if (null !== $this->shardManagerClass) {
            $output['shard_manager_class'] = $this->shardManagerClass;
        }
        if (null !== $this->shardChoser) {
            $output['shard_choser'] = $this->shardChoser;
        }
        if (null !== $this->shardChoserService) {
            $output['shard_choser_service'] = $this->shardChoserService;
        }
        if (null !== $this->keepSlave) {
            $output['keep_slave'] = $this->keepSlave;
        }
        if (null !== $this->keepReplica) {
            $output['keep_replica'] = $this->keepReplica;
        }
        if (null !== $this->options) {
            $output['options'] = $this->options;
        }
        if (null !== $this->mappingTypes) {
            $output['mapping_types'] = $this->mappingTypes;
        }
        if (null !== $this->defaultTableOptions) {
            $output['default_table_options'] = $this->defaultTableOptions;
        }
        if (null !== $this->slaves) {
            $output['slaves'] = array_map(function ($v) { return $v->toArray(); }, $this->slaves);
        }
        if (null !== $this->replicas) {
            $output['replicas'] = array_map(function ($v) { return $v->toArray(); }, $this->replicas);
        }
        if (null !== $this->shards) {
            $output['shards'] = array_map(function ($v) { return $v->toArray(); }, $this->shards);
        }
    
        return $output;
    }
    

}
