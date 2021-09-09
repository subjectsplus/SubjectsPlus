<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class GuideLayout extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('column_class', [$this, 'columnClass']),
        ];
    }

    // TODO: test!
    public function columnClass(string $layout, int $column)
    {
        $sizes = explode('-', $layout);
        return "col-md-".$sizes[$column];

    }

}
