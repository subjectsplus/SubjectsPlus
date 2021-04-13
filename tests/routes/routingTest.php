<?php

namespace App\Tests\Routes;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class RoutingTest extends WebTestCase
{
    public function testRouterCanFindHandleGuideRequests(): void
    {
        $client = static::createClient();

        $client->request('GET', '/subjects/anthropology');

        $this->assertFalse($client->getResponse()->isNotFound());

    }
}