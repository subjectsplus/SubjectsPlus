<?php

namespace App\Twig;

use App\Service\ConfigService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ConfigExtension extends \Twig\Extension\AbstractExtension
{
    private $configService;

    public function __construct(ConfigService $configService) {
        $this->configService = $configService;
    }
    /**
     * @return TwigFunction[]
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('getconfig', [$this, 'getConfigValue']),
        ];
    }

    /**
     * @param string $configKey
     *
     * @return string
     */
    public function getConfigValue( string $configKey): string
    {
        return $this->configService->getConfigValueByKey($configKey);
    }
}