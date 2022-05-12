<?php

namespace App\Service;


use App\Repository\StaffRepository;

class StaffService
{

    private $staffRepository;

    public function __construct(StaffRepository $staffRepository)
    {

    }

}