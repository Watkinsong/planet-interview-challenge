<?php

declare(strict_types=1);

namespace Planet\InterviewChallenge\Domain\Shop\Decorator\Template;

use Planet\InterviewChallenge\Domain\Shop\Cart;
use Planet\InterviewChallenge\Service\TemplateService;

class CartTemplateDecorator
{
    private TemplateService $templateService;
    private Cart $cart;

    public function __construct(TemplateService $templateService, Cart $cart){
        $this->templateService = $templateService;
        $this->cart = $cart;
    }

    public function display(): string
    {
        $decoratedItems = array_map(
            fn ($cartItem) => new CartItemTemplateDecorator($this->templateService, $cartItem),
            $this->cart->getItems()
        );

        $this->templateService->assign('items', $decoratedItems);
        return $this->templateService->fetch('Domain/Shop/Cart.tpl');
    }
}
