<?php

namespace Symfony\Config\DoctrineMigrations\Storage;


use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This class is automatically generated to help creating config.
 *
 * @experimental in 5.3
 */
class TableStorageConfig 
{
    private $tableName;
    private $versionColumnName;
    private $versionColumnLength;
    private $executedAtColumnName;
    private $executionTimeColumnName;
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function tableName($value): self
    {
        $this->tableName = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function versionColumnName($value): self
    {
        $this->versionColumnName = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function versionColumnLength($value): self
    {
        $this->versionColumnLength = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function executedAtColumnName($value): self
    {
        $this->executedAtColumnName = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function executionTimeColumnName($value): self
    {
        $this->executionTimeColumnName = $value;
    
        return $this;
    }
    
    public function __construct(array $value = [])
    {
    
        if (isset($value['table_name'])) {
            $this->tableName = $value['table_name'];
            unset($value['table_name']);
        }
    
        if (isset($value['version_column_name'])) {
            $this->versionColumnName = $value['version_column_name'];
            unset($value['version_column_name']);
        }
    
        if (isset($value['version_column_length'])) {
            $this->versionColumnLength = $value['version_column_length'];
            unset($value['version_column_length']);
        }
    
        if (isset($value['executed_at_column_name'])) {
            $this->executedAtColumnName = $value['executed_at_column_name'];
            unset($value['executed_at_column_name']);
        }
    
        if (isset($value['execution_time_column_name'])) {
            $this->executionTimeColumnName = $value['execution_time_column_name'];
            unset($value['execution_time_column_name']);
        }
    
        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }
    
    
    public function toArray(): array
    {
        $output = [];
        if (null !== $this->tableName) {
            $output['table_name'] = $this->tableName;
        }
        if (null !== $this->versionColumnName) {
            $output['version_column_name'] = $this->versionColumnName;
        }
        if (null !== $this->versionColumnLength) {
            $output['version_column_length'] = $this->versionColumnLength;
        }
        if (null !== $this->executedAtColumnName) {
            $output['executed_at_column_name'] = $this->executedAtColumnName;
        }
        if (null !== $this->executionTimeColumnName) {
            $output['execution_time_column_name'] = $this->executionTimeColumnName;
        }
    
        return $output;
    }
    

}
