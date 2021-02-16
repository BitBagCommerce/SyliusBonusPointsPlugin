<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Controller;

use BitBag\SyliusBonusPointsPlugin\Processor\ResetOrderBonusPointsProcessorInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

final class ResetOrderBonusPointsAction
{
    /** @var RouterInterface */
    private $router;

    /** @var CartContextInterface */
    private $cartContext;

    /** @var ResetOrderBonusPointsProcessorInterface */
    private $resetOrderBonusPointsProcessor;

    public function __construct(
        RouterInterface $router,
        CartContextInterface $cartContext,
        ResetOrderBonusPointsProcessorInterface $resetOrderBonusPointsProcessor
    ) {
        $this->router = $router;
        $this->cartContext = $cartContext;
        $this->resetOrderBonusPointsProcessor = $resetOrderBonusPointsProcessor;
    }

    public function __invoke(Request $request): Response
    {
        $order = $this->cartContext->getCart();

        $this->resetOrderBonusPointsProcessor->process($order);

        return new RedirectResponse($this->router->generate('sylius_shop_cart_summary'));
    }
}