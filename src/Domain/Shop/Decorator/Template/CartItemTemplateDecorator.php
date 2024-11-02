<?php

declare(strict_types=1);

namespace Planet\InterviewChallenge\Domain\Shop\Decorator\Template;

use Planet\InterviewChallenge\Domain\Shop\CartItem;
use Planet\InterviewChallenge\Service\TemplateService;

class CartItemTemplateDecorator
{
    private TemplateService $templateService;
    private CartItem $cartItem;

    public function __construct(TemplateService $templateService, CartItem $cartItem){
        $this->templateService = $templateService;
        $this->cartItem = $cartItem;
    }

    public function display(): string
    {
        $this->templateService->assign('price', $this->cartItem->getPrice());
        $this->templateService->assign('expires', $this->cartItem->getExpires());

        return $this->templateService->fetch('Domain/Shop/CartItem.tpl');
    }
}
