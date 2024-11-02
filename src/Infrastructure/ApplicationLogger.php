<?php

declare(strict_types=1);

namespace Planet\InterviewChallenge\Infrastructure;

use Laminas\Diactoros\Response;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Planet\InterviewChallenge\Service\SmartyTemplateService;
use Ramsey\Uuid\Uuid;
use Throwable;

class ApplicationLogger
{
    private Logger $logger;

    private SmartyTemplateService $smartyTemplateService;

    public function __construct(SmartyTemplateService $smartyTemplateService)
    {
        $this->smartyTemplateService = $smartyTemplateService;

        $this->initLogger();
    }

    public function handleGenericException(Throwable $exception): Response
    {
        $smarty = $this->smartyTemplateService->getSmarty();

        $locator = Uuid::uuid4()->toString();

        self::logException($exception, $locator);

        ob_start();
        try {
            $smarty->assign('locator', $locator);
            $smarty->display('ErrorPages/500.tpl');
            $content = ob_get_contents();
        } catch (Throwable $e) {
            self::logException($e, $locator);
            $content = sprintf('Something went wrong. Error locator: %s', $locator);
        }
        ob_end_clean();

        $response = new Response();
        $response->getBody()->write($content);
        return $response;
    }

    private function logException(Throwable $exception, string $locator): void
    {
        $this->logger->error('', ['locator' => $locator, 'message' => $exception->getMessage(), 'trace' => $exception->getTrace()]);
    }

    private  function initLogger(): void
    {
        $this->logger = new Logger('application');
        $this->logger->pushHandler(new StreamHandler(__DIR__ . '/../../log/error.log'));
    }
}
