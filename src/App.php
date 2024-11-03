<?php

declare(strict_types=1);

namespace Planet\InterviewChallenge;

use DI\Container;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Planet\InterviewChallenge\Infrastructure\ApplicationLogger;
use Planet\InterviewChallenge\Infrastructure\RouteHandler;
use Throwable;

class App
{
    private static ?Container $container = null;

    public static function run(): void
    {
        try {
            $request = ServerRequestFactory::fromGlobals(
                $_SERVER,
                $_GET,
                $_POST,
                $_COOKIE,
                $_FILES
            );

            $response = self::container()->get(RouteHandler::class)->processRequest($request);
        } catch (Throwable $e) {
            $response = self::container()->get(ApplicationLogger::class)->handleGenericException($e);
        }

        (new SapiEmitter())->emit($response);
    }

    public static function container(): Container
    {
        if (null === self::$container) {
            self::$container = new Container();
        }

        return self::$container;
    }
}
