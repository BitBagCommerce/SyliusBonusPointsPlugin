<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

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
    public function __construct(
        private readonly RouterInterface $router,
        private readonly CartContextInterface $cartContext,
        private readonly ResetOrderBonusPointsProcessorInterface $resetOrderBonusPointsProcessor,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $order = $this->cartContext->getCart();

        $this->resetOrderBonusPointsProcessor->process($order);

        return new RedirectResponse($this->router->generate('sylius_shop_cart_summary'));
    }
}
