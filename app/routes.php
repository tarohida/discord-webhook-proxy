<?php

declare(strict_types=1);

use App\Application\Actions\DiscordWebhook\SendRequestToDiscordWebhookAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

return function (\Slim\App $app) {
    $app->post('/webhook/send', SendRequestToDiscordWebhookAction::class);

    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    // Handle favicon.ico requests to return 404 without error
    $app->get('/favicon.ico', function (Request $request, Response $response) {
        return $response->withStatus(404);
    });

    $app->add(function ($request, $handler) {
        $response = $handler->handle($request);
        return $response
            ->withHeader('Access-Control-Allow-Origin', getenv('ALLOW_ORIGIN_URL'))
            ->withHeader(
                'Access-Control-Allow-Headers',
                'X-Requested-With, Content-Type, Accept, Origin, Authorization'
            )
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
    });
};
