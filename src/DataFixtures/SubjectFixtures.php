<?php

namespace App\DataFixtures;

use App\Entity\Subject;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Psr\Log\LoggerInterface;

class SubjectFixtures extends Fixture
{

    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
        
    }
    public function load(ObjectManager $manager)
    {
        $anthropology = new Subject($this->logger);
        $anthropology->setShortform('anth');

        $manager->persist($anthropology);

        $manager->flush();
    }
}
