<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class LinkCheckService
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getUrlStatuses(array $urls): array
    {
        $statuses = [];
        $requests = array_map(function($url) {
            return $this->client->request('HEAD', $url, [
                'verify_peer'=>false,
                'verify_host'=>false,
            ]);
        }, $urls);
        foreach ($this->client->stream($requests) as $response => $chunk)
        {
            $statuses[] = [
                'status' => $response->getStatusCode(),
                'url' => $response->getInfo('url')
            ];
        }
        return $statuses;
    }
}