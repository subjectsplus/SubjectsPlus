<?php

namespace App\Logger;

use App\Entity\Log;
use Doctrine\ORM\EntityManager;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

class MonologDBHandler extends AbstractProcessingHandler
{
    /** @var EntityManager $entityManager */
    private $entityManager;

    public function __construct(EntityManager $entityManager, $level = Logger::ERROR, $bubble = true) {
        parent::__construct($level, $bubble);
        
        $this->entityManager = $entityManager;
    }

    protected function write(array $record): void {
        if (!$this->entityManager->isOpen()) {
            $this->entityManager = $this->entityManager->create(
                $this->entityManager->getConnection(),
                $this->entityManager->getConfiguration(),
                $this->entityManager->getEventManager()
            );
        }

        $logEntry = new Log($record['extra']['token']);
        $logEntry->setMessage($record['message']);
        $logEntry->setLevel($record['level']);
        $logEntry->setLevelName($record['level_name']);
        $logEntry->setClientIp($record['extra']['client_ip']);
        $logEntry->setClientPort($record['extra']['client_port']);
        $logEntry->setMethod($record['extra']['method']);
        $logEntry->setUri($record['extra']['uri']);
        $logEntry->setQueryString($record['extra']['query_string']);

        if (is_array($record['context'])) {
            $logEntry->setContext($record['context']);
        } else {
            $logEntry->setContext([]);
        }

        if (is_array($record['extra']['request'])) {
            $logEntry->setRequest($record['extra']['request']);
        } else {
            $logEntry->setRequest([]);
        }

        $this->entityManager->persist($logEntry);
        $this->entityManager->flush();
    }
}