<?php

namespace App\Tests\Service;

use App\Service\DatabaseService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DatabaseServiceTest extends KernelTestCase
{
    public function testDatabaseUrl()
    {
        self::bootKernel();
        $container = self::$container;

        $proxy_url = 'http://proxy.university.edu/login?url=';
        $service = $container->get(DatabaseService::class);
        $proxy_needed = $service->databaseUrl('http://ebsco.com/database', 0, $proxy_url);
        $expected = 'http://proxy.university.edu/login?url=http://ebsco.com/database';
        $this->assertSame($expected, $proxy_needed);

        $arxiv_url = 'http://arxiv.org';
        $no_proxy_needed = $service->databaseUrl($arxiv_url, 1, $proxy_url);
        $this->assertSame($arxiv_url, $no_proxy_needed);

    }

}
