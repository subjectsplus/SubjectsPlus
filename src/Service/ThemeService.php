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
    public function __construct(ConfigService $configService, $projectDir)
    {
        $this->configService = $configService;
        $this->projectDir = $projectDir;
    }

    public function getThemeId()
    {
        $themeKey = $this->configService->getConfigValueByKey('theme_id');

        return $themeKey ?? 'default';
    }

    public function getThemePath($filepath)
    {
        $themeId = $this->getThemeId();

        $templateDir = $this->projectDir . '/templates';
        // does a theme file exist
        if(file_exists($templateDir . '/themes/um/' . '/' . $filepath)) {
            $themeFile = 'themes/' . $themeId . '/' . $filepath;

        } else {
            $themeFile = 'themes/default/' . $filepath;
        }

        return $themeFile;
    }

}