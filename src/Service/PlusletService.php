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

    public function getPluslet(int $plusletId) {
        return $this->plusletRepository->findOneBy([
            'plusletId' => $plusletId
        ]);
    }
}