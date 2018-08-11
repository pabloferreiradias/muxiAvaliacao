<?php
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\TestHandler;
use Monolog\Logger;

return [
    'defaultChannel' => 'app',
    'channels' => [
        'app' => function ($channel) {
            $logger = new Logger($channel);

            if (env('APP_ENV', 'local') == 'testing') {
                $logger->pushHandler(new TestHandler());
                return $logger;
            }

            $logger->pushHandler(new RotatingFileHandler(
                storage_path('/logs/app.log'),
                7, // days
                Logger::INFO
            ));

            if (env('APP_DEBUG', false)) {
                $logger->pushHandler(new RotatingFileHandler(
                    storage_path('/logs/app.debug'),
                    7, // days
                    Logger::DEBUG
                ));
            }

            return $logger;
        },
        'industries.*' => function ($channel) {
            $logger = new Logger($channel);

            if (env('APP_ENV', 'local') == 'testing') {
                $logger->pushHandler(new TestHandler());
                return $logger;
            }

            $logger->pushHandler(new RotatingFileHandler(
                storage_path('/logs/' . $channel . '.log'),
                7, // days
                Logger::INFO
            ));

            if (env('APP_DEBUG', false)) {
                $logger->pushHandler(new RotatingFileHandler(
                    storage_path('/logs/' . $channel . '.debug'),
                    7, // days
                    Logger::DEBUG
                ));
            }

            return $logger;
        },
    ]
];
