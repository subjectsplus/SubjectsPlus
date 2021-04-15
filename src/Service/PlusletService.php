<?php

namespace App\Service;

class PlusletService
{
    public function plusletClassName(string $type): string
    {
        if ($type == 'Pluslet') {
            return "\SubjectsPlus\Control\Pluslet";
        } else {
            return "\SubjectsPlus\Control\Pluslet\\" . $type;
        }
    }
}
