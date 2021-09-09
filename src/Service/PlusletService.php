<?php

namespace App\Service;

class PlusletService
{
    public function plusletClassName(string $type): string
    {
        if ($type == "Pluslet") {
            return '\\SubjectsPlus\\Control\\Pluslet';
        } elseif ($type == "PlusletInterface") {
            return '\\SubjectsPlus\\Control\\Pluslet\\PlusletInterface';
        } else {
            return '\\SubjectsPlus\\Control\\Pluslet\\Pluslet_'.$type;
        }
    }
}
