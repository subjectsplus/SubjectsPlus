<?php

namespace App\Service;

class ThemeService
{
    /**
     * @var ConfigService
     */
    private $configService;

    /**
     * @param ConfigService $configService
     */
    public function __construct(ConfigService $configService)
    {
        $this->configService = $configService;
    }

    public function getThemeId()
    {
        $themeKey = $this->configService->getConfigValueByKey('theme_id');

        return $themeKey ?? 'default';
    }

    public function getThemePath($filepath)
    {
        $themeId = $this->getThemeId();

        // does a theme file exist
        if(file_exists('themes/' . $themeId . '/' . $filepath)) {
            $themeFile = 'themes/' . $themeId . '/' . $filepath;
        } else {
            $themeFile = 'themes/default/' . $filepath;
        }
        return $themeFile;
    }

}