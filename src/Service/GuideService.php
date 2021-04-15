<?php

namespace App\Service;

class GuideService
{
    // If you'd like to remove some guide types from the public view,
    // modify this property
    public $privateGuideTypes = [
        'A-Z only',
        'Internal',
        'Placeholder'
    ];

    public function guideTypeIsVisible(string $value): bool
    {
        return !in_array($value, $this->privateGuideTypes);
    }
}
