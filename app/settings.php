<?php

declare(strict_types=1);

use App\Application\Settings\Settings;
use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Logger;

return function (ContainerBuilder $containerBuilder) {

    // Global Settings Object
    $containerBuilder->addDefinitions([
        SettingsInterface::class => function () {
            if (
                !getenv('DISCORD_WEBHOOK_URL')
            ) {
                throw new RuntimeException('必須パラメタが設定されていません' . print_r(getenv(), true));
            }
            $production = getenv('PRODUCTION') === 'true';
            return new Settings([
                'displayErrorDetails' => $production, // Should be set to false in production
                'logError' => true,
                'logErrorDetails' => true,
                'logger' => [
                    'name' => $production ? 'production_logger' : 'development_logger',
                    'path' => 'php://stdout',
                    'level' => Logger::DEBUG,
                ],
                'discord' => [
                    'webhook_url' => getenv('DISCORD_WEBHOOK_URL')
                ]
            ]);
        }
    ]);
};
