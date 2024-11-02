<?php

declare(strict_types=1);

namespace Planet\InterviewChallenge\Infrastructure;

use Laminas\Diactoros\Response;
use League\Route\Http\Exception\BadRequestException;
use League\Route\Http\Exception\NotFoundException;
use League\Route\Router;
use Planet\InterviewChallenge\Domain\Shop\Controller\CartController;
use Planet\InterviewChallenge\Service\TemplateService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class RouteHandler
{
    private Router $router;
    private TemplateService $templateService;

    private CartController $cartController;

    public function __construct(TemplateService $templateService, CartController $cartController)
    {
        $this->templateService = $templateService;
        $this->cartController = $cartController;

        $this->initRouter();
    }

    public function processRequest(ServerRequestInterface $request): Response
    {
        try {
            $response = $this->router->dispatch($request);
        } catch (NotFoundException $e) {
            $response = $this->handleNotFoundException();
        } catch (BadRequestException $e) {
            $response = $this->handleBadRequestException($e->getMessage());
        }

        return $response;
    }

    private function initRouter(): void
    {
        $this->router = new Router();

        $this->router->map(
            'GET',
            '/index.php',
            function (ServerRequestInterface $request): ResponseInterface {
                return $this->cartController->showCart($request);
            }
        );
    }

    private function handleNotFoundException(): Response
    {
        ob_start();
        $this->templateService->display('ErrorPages/404.tpl');
        $content = ob_get_contents();
        ob_end_clean();

        $response = new Response();
        $response->getBody()->write($content);
        return $response;
    }

    private function handleBadRequestException(string $message): Response
    {
        ob_start();
        $this->templateService->assign('message', $message);
        $this->templateService->display('ErrorPages/400.tpl');
        $content = ob_get_contents();
        ob_end_clean();

        $response = new Response();
        $response->getBody()->write($content);
        return $response;
    }
}
