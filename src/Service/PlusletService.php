<?php

namespace App\Service;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Pluslet;

class PlusletService
{
    private $plusletRepository;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->plusletRepository = $entityManager->getRepository(Pluslet::class);
    }

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

    public function getPluslet(int $plusletId) {
        return $this->plusletRepository->findOneBy([
            'plusletId' => $plusletId
        ]);
    }
}