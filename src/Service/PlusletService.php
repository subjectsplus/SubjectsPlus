<?php

namespace App\Service;

class PlusletService
{
    public function plusletClassName(string $type): string
    {
        if ('Pluslet' == $type) {
            return '\\SubjectsPlus\\Control\\Pluslet';
        } elseif ('Clone' == $type) {
            return '\\SubjectsPlus\\Control\\Pluslet\\Pluslet_Clone';
        } elseif (is_numeric($type)) {
            return '\\SubjectsPlus\\Control\\Pluslet\\Pluslet_'.$type;
        } else {
            return '\\SubjectsPlus\\Control\\Pluslet\\'.$type;
        }
    }
}
