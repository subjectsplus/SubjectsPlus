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

        $logEntry = new Log();
        $logEntry->setMessage($record['message']);
        $logEntry->setLevel($record['level']);
        $logEntry->setLevelName($record['level_name']);
        
        if(is_array($record['extra'])) {
            $logEntry->setExtra($record['extra']);
        } else {
            $logEntry->setExtra([]);
        }

        if (is_array($record['context'])) {
            $logEntry->setContext($record['context']);
        } else {
            $logEntry->setContext([]);
        }

        $this->entityManager->persist($logEntry);
        $this->entityManager->flush();
    }
}