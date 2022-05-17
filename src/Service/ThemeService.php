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

    /**
     * @return string
     */
    public function getThemeId()
    {
        $themeKey = $this->configService->getConfigValueByKey('theme_id');

        return $themeKey ?? 'default';
    }

    /**
     * @param $filepath
     *
     * @return mixed|string
     */
    public function getThemePath($filepath)
    {
        $themeId = $this->getThemeId();
        $templateDir = $this->projectDir . '/templates';
        $themeDir = $templateDir . '/frontend/themes/' . $themeId . '/';

        // does a theme file exist
        if(file_exists($themeDir . '/' . $filepath))
        {
            $themeFile =   'frontend/themes/' .$themeId . '/' . $filepath;
        } else {
            $themeFile = 'frontend/' . $filepath;
        }

        return $themeFile;
    }

}