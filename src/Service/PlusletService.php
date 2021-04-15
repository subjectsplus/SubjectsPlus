<?php

namespace App\Service;

class PlusletService
{
    public function plusletClassName(string $type): string
    {
        if ('Pluslet' == $type) {
            return "\SubjectsPlus\Control\Pluslet";
        } else {
            return "\SubjectsPlus\Control\Pluslet\\".$type;
        }
    }
}
