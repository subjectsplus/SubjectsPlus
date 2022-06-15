<?php

namespace App\Service;

use App\Entity\Record;
use App\Repository\RecordRepository;

class RecordService
{

    private $_configService;

    private $_recordRepo;

    public function __construct(
        ConfigService $configService,
        RecordRepository $recordRepository
    ) {
        $this->_configService = $configService;
        $this->_recordRepo = $recordRepository;
    }

    public function getAlphaLetters()
    {
        return $this->_recordRepo->getLetters();
    }
}