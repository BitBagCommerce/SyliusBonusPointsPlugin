<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Controller;

use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsInterface;
use BitBag\SyliusBonusPointsPlugin\Purifier\OrderBonusPointsPurifierInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

final class ResetOrderBonusPointsAction
{
    /** @var RouterInterface */
    private $router;

    /** @var OrderBonusPointsPurifierInterface */
    private $orderBonusPointsPurifier;

    /** @var CartContextInterface */
    private $cartContext;

    /** @var RepositoryInterface */
    private $bonusPointsRepository;

    public function __construct(
        RouterInterface $router,
        OrderBonusPointsPurifierInterface $orderBonusPointsPurifier,
        CartContextInterface $cartContext,
        RepositoryInterface $bonusPointsRepository
    ) {
        $this->router = $router;
        $this->orderBonusPointsPurifier = $orderBonusPointsPurifier;
        $this->cartContext = $cartContext;
        $this->bonusPointsRepository = $bonusPointsRepository;
    }

    public function __invoke(Request $request): Response
    {
        $order = $this->cartContext->getCart();

        /** @var BonusPointsInterface $bonusPoints */
        $bonusPoints = $this->bonusPointsRepository->findOneBy(['order' => $order, 'isUsed' => true]);

        if (null !== $bonusPoints) {
            $this->orderBonusPointsPurifier->purify($bonusPoints);
        }

        return new RedirectResponse($this->router->generate('sylius_shop_cart_summary'));
    }
}