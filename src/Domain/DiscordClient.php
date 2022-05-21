<?php

declare(strict_types=1);

namespace App\Domain;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class DiscordClient
{
    private Client $curl_client;
    private string $discord_webhook_url;

    public function __construct(Client $curl_client, string $discord_webhook_url)
    {
        $this->curl_client = $curl_client;
        $this->discord_webhook_url = $discord_webhook_url;
    }

    public function notify(string $url): ResponseInterface
    {
        return $this->curl_client->post(
            $this->discord_webhook_url,
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'user_name' => "test",
                    'content' => $url
                ],
            ]
        );
    }
}
