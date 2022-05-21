<?php

declare(strict_types=1);

namespace App\Application\Actions\DiscordWebhook;

use App\Application\Actions\Action;
use App\Domain\DiscordClient;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;

class SendRequestToDiscordWebhookAction extends Action
{
    private DiscordClient $client;

    public function __construct(LoggerInterface $logger, DiscordClient $client)
    {
        parent::__construct($logger);
        $this->client = $client;
    }

    protected function action(): Response
    {
        $data = $this->request->getParsedBody();
        $content = $this->getDiscordWebhookRequestData($data);
        $this->client->notify($content);
        return $this->response;
    }

    private function getDiscordWebhookRequestData($data): string
    {
        if (!isset($data['content'])) {
            throw new HttpBadRequestException($this->request, "contentが足りないよ");
        }
        return $data['content'];
    }
}
