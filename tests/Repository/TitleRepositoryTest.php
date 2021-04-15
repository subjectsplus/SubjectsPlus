<?php

namespace App\Tests\Repository;

use App\Entity\Title;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TitleRepositoryTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testNewDatabasesFetch()
    {
        $titles = $this->entityManager
            ->getRepository(Title::class)
            ->newPublicDatabases(1)
        ;

        $this->assertSame('Jstor', $titles[0]['title']);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}


?>