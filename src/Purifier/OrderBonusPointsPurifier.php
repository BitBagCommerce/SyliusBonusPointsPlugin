<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Purifier;

use BitBag\SyliusBonusPointsPlugin\Context\CustomerBonusPointsContextInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\AdjustmentInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\CustomerBonusPointsInterface;
use BitBag\SyliusBonusPointsPlugin\Repository\BonusPointsRepositoryInterface;
use Sylius\Component\Core\Model\OrderInterface;

final class OrderBonusPointsPurifier implements OrderBonusPointsPurifierInterface
{
    /** @var CustomerBonusPointsContextInterface */
    private $customerBonusPointsContext;

    /** @var BonusPointsRepositoryInterface */
    private $bonusPointsRepository;

    public function __construct(
        CustomerBonusPointsContextInterface $customerBonusPointsContext,
        BonusPointsRepositoryInterface $bonusPointsRepository
    ) {
        $this->customerBonusPointsContext = $customerBonusPointsContext;
        $this->bonusPointsRepository = $bonusPointsRepository;
    }

    public function purify(
        BonusPointsInterface $bonusPoints,
        CustomerBonusPointsInterface $customerBonusPoints = null
    ): void {
        /** @var OrderInterface $order */
        $order = $bonusPoints->getOrder();

        if (null === $customerBonusPoints) {
            $customerBonusPoints = $this->customerBonusPointsContext->getCustomerBonusPoints();
        }

        if ($order->getCustomer() !== $customerBonusPoints->getCustomer()) {
            return;
        }

        $order->removeAdjustmentsRecursively(AdjustmentInterface::ORDER_BONUS_POINTS_ADJUSTMENT);
        $customerBonusPoints->removeBonusPointsUsed($bonusPoints);

        $this->bonusPointsRepository->add($customerBonusPoints);
        $this->bonusPointsRepository->add($order);
        $this->bonusPointsRepository->remove($bonusPoints);
    }
}
