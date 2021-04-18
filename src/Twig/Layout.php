<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class Layout extends AbstractExtension
{
    private $path_to_header = "public/shared/header.html.twig";
    private $projectDir;

    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('include_header_from_theme', [$this, 'includeHeaderFromTheme']),
            new TwigFunction('header_exists_for_theme', [$this, 'headerExistsForTheme']),
        ];
    }

    public function includeHeaderFromTheme(string $themeName)
    {
        # TODO: add logic in case the theme file does not exist

        $loader = new \Twig\Loader\FilesystemLoader($this->themeDir($themeName), $this->projectDir);
        $specializedEnvironment = new \Twig\Environment($loader);
        return twig_include($specializedEnvironment,
                            [],
                            $this->path_to_header,
                            [],
                            false);
    }

    public function headerExistsForTheme(string $themeName)
    {
        return file_exists($this->projectDir . "/" . $this->themeDir($themeName) . $this->path_to_header);
    }

    private function themeDir(string $themeName)
    {
        return "templates/themes/$themeName/";
    }


}
