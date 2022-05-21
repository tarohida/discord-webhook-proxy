<?php

declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use App\Domain\DiscordClient;
use DI\ContainerBuilder;
use GuzzleHttp\Client;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $loggerSettings = $settings->get('logger');
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $formatter = new LineFormatter();
            $formatter->includeStacktraces(true);
            $logger->pushHandler($handler->setFormatter($formatter));

            return $logger;
        },
        DiscordClient::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);
            $discord_webhook_url = $settings->get('discord')['webhook_url'];
            return new DiscordClient(new Client(), $discord_webhook_url);
        }
    ]);
};
