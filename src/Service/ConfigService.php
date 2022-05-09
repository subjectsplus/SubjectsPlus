<?php

namespace App\Service;

use App\Repository\ConfigRepository;
use Doctrine\ORM\EntityManagerInterface;

class ConfigService
{
    /**
     * Entity Manager.
     *
     * @var EntityManagerInterface $entityManager
     */

    private $entityManager;

    /**
     * ConfigRepository
     *
     * @var ConfigRepository
     */
    private $configRepository;

    public function __construct(EntityManagerInterface $entityManager, ConfigRepository $configRepository)
    {
        $this->entityManager = $entityManager;
        $this->configRepository = $configRepository;
    }

    /**
     * @param $optionKey
     *
     * @return string|null
     */
    public function getConfigValueByKey($optionKey)
    {
        $config = $this->configRepository->findOneBy(array('option_key' => $optionKey), array());
        return $config->getOptionValue();
    }
}