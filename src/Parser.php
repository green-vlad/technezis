<?php
declare(strict_types=1);

namespace App;

use Exception;
use GuzzleHttp\Client;

class Parser
{
    private const IMG_REGEX = '/(\'|\")([^\s\\\"]+\.(jpg|jpeg))(\'|\")/m';
    private Client $client;
    private string $url;
    public function __construct(Client $client, string $url)
    {
        $this->client = $client;
        $this->url = $url;
    }

    public function parse(): array
    {
        $response = $this->client->request('GET', $this->url);
        if ($response->getStatusCode() !== 200) {
            throw new Exception("Error getting $this->url");
        }
        $html = $response->getBody()->getContents();
        preg_match_all(self::IMG_REGEX, $html, $matches);
        $result = [];
        foreach ($matches[2] as $match) {
            $head = $this->client->request('HEAD', $this->url . $match);
            $result[$match] = $head->getHeader('Content-Length')[0];
        }
        return $result;
    }
}