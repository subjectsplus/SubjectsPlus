<?php

namespace ContainerMS5FBSd;
include_once \dirname(__DIR__, 4).'/vendor/doctrine/persistence/lib/Doctrine/Persistence/ObjectManager.php';
include_once \dirname(__DIR__, 4).'/vendor/doctrine/orm/lib/Doctrine/ORM/EntityManagerInterface.php';
include_once \dirname(__DIR__, 4).'/vendor/doctrine/orm/lib/Doctrine/ORM/EntityManager.php';

class EntityManager_9a5be93 extends \Doctrine\ORM\EntityManager implements \ProxyManager\Proxy\VirtualProxyInterface
{
    /**
     * @var \Doctrine\ORM\EntityManager|null wrapped object, if the proxy is initialized
     */
    private $valueHolder6bce4 = null;

    /**
     * @var \Closure|null initializer responsible for generating the wrapped object
     */
    private $initializer84067 = null;

    /**
     * @var bool[] map of public properties of the parent class
     */
    private static $publicPropertiesf953b = [
        
    ];

    public function getConnection()
    {
        $this->initializer84067 && ($this->initializer84067->__invoke($valueHolder6bce4, $this, 'getConnection', array(), $this->initializer84067) || 1) && $this->valueHolder6bce4 = $valueHolder6bce4;

        return $this->valueHolder6bce4->getConnection();
    }

    public function getMetadataFactory()
    {
        $this->initializer84067 && ($this->initializer84067->__invoke($valueHolder6bce4, $this, 'getMetadataFactory', array(), $this->initializer84067) || 1) && $this->valueHolder6bce4 = $valueHolder6bce4;

        return $this->valueHolder6bce4->getMetadataFactory();
    }

    public function getExpressionBuilder()
    {
        $this->initializer84067 && ($this->initializer84067->__invoke($valueHolder6bce4, $this, 'getExpressionBuilder', array(), $this->initializer84067) || 1) && $this->valueHolder6bce4 = $valueHolder6bce4;

        return $this->valueHolder6bce4->getExpressionBuilder();
    }

    public function beginTransaction()
    {
        $this->initializer84067 && ($this->initializer84067->__invoke($valueHolder6bce4, $this, 'beginTransaction', array(), $this->initializer84067) || 1) && $this->valueHolder6bce4 = $valueHolder6bce4;

        return $this->valueHolder6bce4->beginTransaction();
    }

    public function getCache()
    {
        $this->initializer84067 && ($this->initializer84067->__invoke($valueHolder6bce4, $this, 'getCache', array(), $this->initializer84067) || 1) && $this->valueHolder6bce4 = $valueHolder6bce4;

        return $this->valueHolder6bce4->getCache();
    }

    public function transactional($func)
    {
        $this->initializer84067 && ($this->initializer84067->__invoke($valueHolder6bce4, $this, 'transactional', array('func' => $func), $this->initializer84067) || 1) && $this->valueHolder6bce4 = $valueHolder6bce4;

        return $this->valueHolder6bce4->transactional($func);
    }

    public function commit()
    {
        $this->initializer84067 && ($this->initializer84067->__invoke($valueHolder6bce4, $this, 'commit', array(), $this->initializer84067) || 1) && $this->valueHolder6bce4 = $valueHolder6bce4;

        return $this->valueHolder6bce4->commit();
    }

    public function rollback()
    {
        $this->initializer84067 && ($this->initializer84067->__invoke($valueHolder6bce4, $this, 'rollback', array(), $this->initializer84067) || 1) && $this->valueHolder6bce4 = $valueHolder6bce4;

        return $this->valueHolder6bce4->rollback();
    }

    public function getClassMetadata($className)
    {
        $this->initializer84067 && ($this->initializer84067->__invoke($valueHolder6bce4, $this, 'getClassMetadata', array('className' => $className), $this->initializer84067) || 1) && $this->valueHolder6bce4 = $valueHolder6bce4;

        return $this->valueHolder6bce4->getClassMetadata($className);
    }

    public function createQuery($dql = '')
    {
        $this->initializer84067 && ($this->initializer84067->__invoke($valueHolder6bce4, $this, 'createQuery', array('dql' => $dql), $this->initializer84067) || 1) && $this->valueHolder6bce4 = $valueHolder6bce4;

        return $this->valueHolder6bce4->createQuery($dql);
    }

    public function createNamedQuery($name)
    {
        $this->initializer84067 && ($this->initializer84067->__invoke($valueHolder6bce4, $this, 'createNamedQuery', array('name' => $name), $this->initializer84067) || 1) && $this->valueHolder6bce4 = $valueHolder6bce4;

        return $this->valueHolder6bce4->createNamedQuery($name);
    }

    public function createNativeQuery($sql, \Doctrine\ORM\Query\ResultSetMapping $rsm)
    {
        $this->initializer84067 && ($this->initializer84067->__invoke($valueHolder6bce4, $this, 'createNativeQuery', array('sql' => $sql, 'rsm' => $rsm), $this->initializer84067) || 1) && $this->valueHolder6bce4 = $valueHolder6bce4;

        return $this->valueHolder6bce4->createNativeQuery($sql, $rsm);
    }

    public function createNamedNativeQuery($name)
    {
        $this->initializer84067 && ($this->initializer84067->__invoke($valueHolder6bce4, $this, 'createNamedNativeQuery', array('name' => $name), $this->initializer84067) || 1) && $this->valueHolder6bce4 = $valueHolder6bce4;

        return $this->valueHolder6bce4->createNamedNativeQuery($name);
    }

    public function createQueryBuilder()
    {
        $this->initializer84067 && ($this->initializer84067->__invoke($valueHolder6bce4, $this, 'createQueryBuilder', array(), $this->initializer84067) || 1) && $this->valueHolder6bce4 = $valueHolder6bce4;

        return $this->valueHolder6bce4->createQueryBuilder();
    }

    public function flush($entity = null)
    {
        $this->initializer84067 && ($this->initializer84067->__invoke($valueHolder6bce4, $this, 'flush', array('entity' => $entity), $this->initializer84067) || 1) && $this->valueHolder6bce4 = $valueHolder6bce4;

        return $this->valueHolder6bce4->flush($entity);
    }

    public function find($className, $id, $lockMode = null, $lockVersion = null)
    {
        $this->initializer84067 && ($this->initializer84067->__invoke($valueHolder6bce4, $this, 'find', array('className' => $className, 'id' => $id, 'lockMode' => $lockMode, 'lockVersion' => $lockVersion), $this->initializer84067) || 1) && $this->valueHolder6bce4 = $valueHolder6bce4;

        return $this->valueHolder6bce4->find($className, $id, $lockMode, $lockVersion);
    }

    public function getReference($entityName, $id)
    {
        $this->initializer84067 && ($this->initializer84067->__invoke($valueHolder6bce4, $this, 'getReference', array('entityName' => $entityName, 'id' => $id), $this->initializer84067) || 1) && $this->valueHolder6bce4 = $valueHolder6bce4;

        return $this->valueHolder6bce4->getReference($entityName, $id);
    }

    public function getPartialReference($entityName, $identifier)
    {
        $this->initializer84067 && ($this->initializer84067->__invoke($valueHolder6bce4, $this, 'getPartialReference', array('entityName' => $entityName, 'identifier' => $identifier), $this->initializer84067) || 1) && $this->valueHolder6bce4 = $valueHolder6bce4;

        return $this->valueHolder6bce4->getPartialReference($entityName, $identifier);
    }

    public function clear($entityName = null)
    {
        $this->initializer84067 && ($this->initializer84067->__invoke($valueHolder6bce4, $this, 'clear', array('entityName' => $entityName), $this->initializer84067) || 1) && $this->valueHolder6bce4 = $valueHolder6bce4;

        return $this->valueHolder6bce4->clear($entityName);
    }

    public function close()
    {
        $this->initializer84067 && ($this->initializer84067->__invoke($valueHolder6bce4, $this, 'close', array(), $this->initializer84067) || 1) && $this->valueHolder6bce4 = $valueHolder6bce4;

        return $this->valueHolder6bce4->close();
    }

    public function persist($entity)
    {
        $this->initializer84067 && ($this->initializer84067->__invoke($valueHolder6bce4, $this, 'persist', array('entity' => $entity), $this->initializer84067) || 1) && $this->valueHolder6bce4 = $valueHolder6bce4;

        return $this->valueHolder6bce4->persist($entity);
    }

    public function remove($entity)
    {
        $this->initializer84067 && ($this->initializer84067->__invoke($valueHolder6bce4, $this, 'remove', array('entity' => $entity), $this->initializer84067) || 1) && $this->valueHolder6bce4 = $valueHolder6bce4;

        return $this->valueHolder6bce4->remove($entity);
    }

    public function refresh($entity)
    {
        $this->initializer84067 && ($this->initializer84067->__invoke($valueHolder6bce4, $this, 'refresh', array('entity' => $entity), $this->initializer84067) || 1) && $this->valueHolder6bce4 = $valueHolder6bce4;

        return $this->valueHolder6bce4->refresh($entity);
    }

    public function detach($entity)
    {
        $this->initializer84067 && ($this->initializer84067->__invoke($valueHolder6bce4, $this, 'detach', array('entity' => $entity), $this->initializer84067) || 1) && $this->valueHolder6bce4 = $valueHolder6bce4;

        return $this->valueHolder6bce4->detach($entity);
    }

    public function merge($entity)
    {
        $this->initializer84067 && ($this->initializer84067->__invoke($valueHolder6bce4, $this, 'merge', array('entity' => $entity), $this->initializer84067) || 1) && $this->valueHolder6bce4 = $valueHolder6bce4;

        return $this->valueHolder6bce4->merge($entity);
    }

    public function copy($entity, $deep = false)
    {
        $this->initializer84067 && ($this->initializer84067->__invoke($valueHolder6bce4, $this, 'copy', array('entity' => $entity, 'deep' => $deep), $this->initializer84067) || 1) && $this->valueHolder6bce4 = $valueHolder6bce4;

        return $this->valueHolder6bce4->copy($entity, $deep);
    }

    public function lock($entity, $lockMode, $lockVersion = null)
    {
        $this->initializer84067 && ($this->initializer84067->__invoke($valueHolder6bce4, $this, 'lock', array('entity' => $entity, 'lockMode' => $lockMode, 'lockVersion' => $lockVersion), $this->initializer84067) || 1) && $this->valueHolder6bce4 = $valueHolder6bce4;

        return $this->valueHolder6bce4->lock($entity, $lockMode, $lockVersion);
    }

    public function getRepository($entityName)
    {
        $this->initializer84067 && ($this->initializer84067->__invoke($valueHolder6bce4, $this, 'getRepository', array('entityName' => $entityName), $this->initializer84067) || 1) && $this->valueHolder6bce4 = $valueHolder6bce4;

        return $this->valueHolder6bce4->getRepository($entityName);
    }

    public function contains($entity)
    {
        $this->initializer84067 && ($this->initializer84067->__invoke($valueHolder6bce4, $this, 'contains', array('entity' => $entity), $this->initializer84067) || 1) && $this->valueHolder6bce4 = $valueHolder6bce4;

        return $this->valueHolder6bce4->contains($entity);
    }

    public function getEventManager()
    {
        $this->initializer84067 && ($this->initializer84067->__invoke($valueHolder6bce4, $this, 'getEventManager', array(), $this->initializer84067) || 1) && $this->valueHolder6bce4 = $valueHolder6bce4;

        return $this->valueHolder6bce4->getEventManager();
    }

    public function getConfiguration()
    {
        $this->initializer84067 && ($this->initializer84067->__invoke($valueHolder6bce4, $this, 'getConfiguration', array(), $this->initializer84067) || 1) && $this->valueHolder6bce4 = $valueHolder6bce4;

        return $this->valueHolder6bce4->getConfiguration();
    }

    public function isOpen()
    {
        $this->initializer84067 && ($this->initializer84067->__invoke($valueHolder6bce4, $this, 'isOpen', array(), $this->initializer84067) || 1) && $this->valueHolder6bce4 = $valueHolder6bce4;

        return $this->valueHolder6bce4->isOpen();
    }

    public function getUnitOfWork()
    {
        $this->initializer84067 && ($this->initializer84067->__invoke($valueHolder6bce4, $this, 'getUnitOfWork', array(), $this->initializer84067) || 1) && $this->valueHolder6bce4 = $valueHolder6bce4;

        return $this->valueHolder6bce4->getUnitOfWork();
    }

    public function getHydrator($hydrationMode)
    {
        $this->initializer84067 && ($this->initializer84067->__invoke($valueHolder6bce4, $this, 'getHydrator', array('hydrationMode' => $hydrationMode), $this->initializer84067) || 1) && $this->valueHolder6bce4 = $valueHolder6bce4;

        return $this->valueHolder6bce4->getHydrator($hydrationMode);
    }

    public function newHydrator($hydrationMode)
    {
        $this->initializer84067 && ($this->initializer84067->__invoke($valueHolder6bce4, $this, 'newHydrator', array('hydrationMode' => $hydrationMode), $this->initializer84067) || 1) && $this->valueHolder6bce4 = $valueHolder6bce4;

        return $this->valueHolder6bce4->newHydrator($hydrationMode);
    }

    public function getProxyFactory()
    {
        $this->initializer84067 && ($this->initializer84067->__invoke($valueHolder6bce4, $this, 'getProxyFactory', array(), $this->initializer84067) || 1) && $this->valueHolder6bce4 = $valueHolder6bce4;

        return $this->valueHolder6bce4->getProxyFactory();
    }

    public function initializeObject($obj)
    {
        $this->initializer84067 && ($this->initializer84067->__invoke($valueHolder6bce4, $this, 'initializeObject', array('obj' => $obj), $this->initializer84067) || 1) && $this->valueHolder6bce4 = $valueHolder6bce4;

        return $this->valueHolder6bce4->initializeObject($obj);
    }

    public function getFilters()
    {
        $this->initializer84067 && ($this->initializer84067->__invoke($valueHolder6bce4, $this, 'getFilters', array(), $this->initializer84067) || 1) && $this->valueHolder6bce4 = $valueHolder6bce4;

        return $this->valueHolder6bce4->getFilters();
    }

    public function isFiltersStateClean()
    {
        $this->initializer84067 && ($this->initializer84067->__invoke($valueHolder6bce4, $this, 'isFiltersStateClean', array(), $this->initializer84067) || 1) && $this->valueHolder6bce4 = $valueHolder6bce4;

        return $this->valueHolder6bce4->isFiltersStateClean();
    }

    public function hasFilters()
    {
        $this->initializer84067 && ($this->initializer84067->__invoke($valueHolder6bce4, $this, 'hasFilters', array(), $this->initializer84067) || 1) && $this->valueHolder6bce4 = $valueHolder6bce4;

        return $this->valueHolder6bce4->hasFilters();
    }

    /**
     * Constructor for lazy initialization
     *
     * @param \Closure|null $initializer
     */
    public static function staticProxyConstructor($initializer)
    {
        static $reflection;

        $reflection = $reflection ?? new \ReflectionClass(__CLASS__);
        $instance   = $reflection->newInstanceWithoutConstructor();

        \Closure::bind(function (\Doctrine\ORM\EntityManager $instance) {
            unset($instance->config, $instance->conn, $instance->metadataFactory, $instance->unitOfWork, $instance->eventManager, $instance->proxyFactory, $instance->repositoryFactory, $instance->expressionBuilder, $instance->closed, $instance->filterCollection, $instance->cache);
        }, $instance, 'Doctrine\\ORM\\EntityManager')->__invoke($instance);

        $instance->initializer84067 = $initializer;

        return $instance;
    }

    protected function __construct(\Doctrine\DBAL\Connection $conn, \Doctrine\ORM\Configuration $config, \Doctrine\Common\EventManager $eventManager)
    {
        static $reflection;

        if (! $this->valueHolder6bce4) {
            $reflection = $reflection ?? new \ReflectionClass('Doctrine\\ORM\\EntityManager');
            $this->valueHolder6bce4 = $reflection->newInstanceWithoutConstructor();
        \Closure::bind(function (\Doctrine\ORM\EntityManager $instance) {
            unset($instance->config, $instance->conn, $instance->metadataFactory, $instance->unitOfWork, $instance->eventManager, $instance->proxyFactory, $instance->repositoryFactory, $instance->expressionBuilder, $instance->closed, $instance->filterCollection, $instance->cache);
        }, $this, 'Doctrine\\ORM\\EntityManager')->__invoke($this);

        }

        $this->valueHolder6bce4->__construct($conn, $config, $eventManager);
    }

    public function & __get($name)
    {
        $this->initializer84067 && ($this->initializer84067->__invoke($valueHolder6bce4, $this, '__get', ['name' => $name], $this->initializer84067) || 1) && $this->valueHolder6bce4 = $valueHolder6bce4;

        if (isset(self::$publicPropertiesf953b[$name])) {
            return $this->valueHolder6bce4->$name;
        }

        $realInstanceReflection = new \ReflectionClass('Doctrine\\ORM\\EntityManager');

        if (! $realInstanceReflection->hasProperty($name)) {
            $targetObject = $this->valueHolder6bce4;

            $backtrace = debug_backtrace(false, 1);
            trigger_error(
                sprintf(
                    'Undefined property: %s::$%s in %s on line %s',
                    $realInstanceReflection->getName(),
                    $name,
                    $backtrace[0]['file'],
                    $backtrace[0]['line']
                ),
                \E_USER_NOTICE
            );
            return $targetObject->$name;
        }

        $targetObject = $this->valueHolder6bce4;
        $accessor = function & () use ($targetObject, $name) {
            return $targetObject->$name;
        };
        $backtrace = debug_backtrace(true, 2);
        $scopeObject = isset($backtrace[1]['object']) ? $backtrace[1]['object'] : new \ProxyManager\Stub\EmptyClassStub();
        $accessor = $accessor->bindTo($scopeObject, get_class($scopeObject));
        $returnValue = & $accessor();

        return $returnValue;
    }

    public function __set($name, $value)
    {
        $this->initializer84067 && ($this->initializer84067->__invoke($valueHolder6bce4, $this, '__set', array('name' => $name, 'value' => $value), $this->initializer84067) || 1) && $this->valueHolder6bce4 = $valueHolder6bce4;

        $realInstanceReflection = new \ReflectionClass('Doctrine\\ORM\\EntityManager');

        if (! $realInstanceReflection->hasProperty($name)) {
            $targetObject = $this->valueHolder6bce4;

            $targetObject->$name = $value;

            return $targetObject->$name;
        }

        $targetObject = $this->valueHolder6bce4;
        $accessor = function & () use ($targetObject, $name, $value) {
            $targetObject->$name = $value;

            return $targetObject->$name;
        };
        $backtrace = debug_backtrace(true, 2);
        $scopeObject = isset($backtrace[1]['object']) ? $backtrace[1]['object'] : new \ProxyManager\Stub\EmptyClassStub();
        $accessor = $accessor->bindTo($scopeObject, get_class($scopeObject));
        $returnValue = & $accessor();

        return $returnValue;
    }

    public function __isset($name)
    {
        $this->initializer84067 && ($this->initializer84067->__invoke($valueHolder6bce4, $this, '__isset', array('name' => $name), $this->initializer84067) || 1) && $this->valueHolder6bce4 = $valueHolder6bce4;

        $realInstanceReflection = new \ReflectionClass('Doctrine\\ORM\\EntityManager');

        if (! $realInstanceReflection->hasProperty($name)) {
            $targetObject = $this->valueHolder6bce4;

            return isset($targetObject->$name);
        }

        $targetObject = $this->valueHolder6bce4;
        $accessor = function () use ($targetObject, $name) {
            return isset($targetObject->$name);
        };
        $backtrace = debug_backtrace(true, 2);
        $scopeObject = isset($backtrace[1]['object']) ? $backtrace[1]['object'] : new \ProxyManager\Stub\EmptyClassStub();
        $accessor = $accessor->bindTo($scopeObject, get_class($scopeObject));
        $returnValue = $accessor();

        return $returnValue;
    }

    public function __unset($name)
    {
        $this->initializer84067 && ($this->initializer84067->__invoke($valueHolder6bce4, $this, '__unset', array('name' => $name), $this->initializer84067) || 1) && $this->valueHolder6bce4 = $valueHolder6bce4;

        $realInstanceReflection = new \ReflectionClass('Doctrine\\ORM\\EntityManager');

        if (! $realInstanceReflection->hasProperty($name)) {
            $targetObject = $this->valueHolder6bce4;

            unset($targetObject->$name);

            return;
        }

        $targetObject = $this->valueHolder6bce4;
        $accessor = function () use ($targetObject, $name) {
            unset($targetObject->$name);

            return;
        };
        $backtrace = debug_backtrace(true, 2);
        $scopeObject = isset($backtrace[1]['object']) ? $backtrace[1]['object'] : new \ProxyManager\Stub\EmptyClassStub();
        $accessor = $accessor->bindTo($scopeObject, get_class($scopeObject));
        $accessor();
    }

    public function __clone()
    {
        $this->initializer84067 && ($this->initializer84067->__invoke($valueHolder6bce4, $this, '__clone', array(), $this->initializer84067) || 1) && $this->valueHolder6bce4 = $valueHolder6bce4;

        $this->valueHolder6bce4 = clone $this->valueHolder6bce4;
    }

    public function __sleep()
    {
        $this->initializer84067 && ($this->initializer84067->__invoke($valueHolder6bce4, $this, '__sleep', array(), $this->initializer84067) || 1) && $this->valueHolder6bce4 = $valueHolder6bce4;

        return array('valueHolder6bce4');
    }

    public function __wakeup()
    {
        \Closure::bind(function (\Doctrine\ORM\EntityManager $instance) {
            unset($instance->config, $instance->conn, $instance->metadataFactory, $instance->unitOfWork, $instance->eventManager, $instance->proxyFactory, $instance->repositoryFactory, $instance->expressionBuilder, $instance->closed, $instance->filterCollection, $instance->cache);
        }, $this, 'Doctrine\\ORM\\EntityManager')->__invoke($this);
    }

    public function setProxyInitializer(\Closure $initializer = null) : void
    {
        $this->initializer84067 = $initializer;
    }

    public function getProxyInitializer() : ?\Closure
    {
        return $this->initializer84067;
    }

    public function initializeProxy() : bool
    {
        return $this->initializer84067 && ($this->initializer84067->__invoke($valueHolder6bce4, $this, 'initializeProxy', array(), $this->initializer84067) || 1) && $this->valueHolder6bce4 = $valueHolder6bce4;
    }

    public function isProxyInitialized() : bool
    {
        return null !== $this->valueHolder6bce4;
    }

    public function getWrappedValueHolderValue()
    {
        return $this->valueHolder6bce4;
    }
}

if (!\class_exists('EntityManager_9a5be93', false)) {
    \class_alias(__NAMESPACE__.'\\EntityManager_9a5be93', 'EntityManager_9a5be93', false);
}
