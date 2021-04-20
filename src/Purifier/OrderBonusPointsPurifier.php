<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Purifier;

use BitBag\SyliusBonusPointsPlugin\Context\CustomerBonusPointsContextInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\AdjustmentInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\CustomerBonusPointsInterface;
use Doctrine\Persistence\ObjectManager;

final class OrderBonusPointsPurifier implements OrderBonusPointsPurifierInterface
{
    /** @var CustomerBonusPointsContextInterface */
    private $customerBonusPointsContext;

    /** @var ObjectManager */
    private $persistenceManager;

    public function __construct(
        CustomerBonusPointsContextInterface $customerBonusPointsContext,
        ObjectManager $persistenceManager
    ) {
        $this->customerBonusPointsContext = $customerBonusPointsContext;
        $this->persistenceManager = $persistenceManager;
    }

    public function purify(
        BonusPointsInterface $bonusPoints,
        CustomerBonusPointsInterface $customerBonusPoints = null
    ): void {
        $order = $bonusPoints->getOrder();

        if (null === $customerBonusPoints) {
            $customerBonusPoints = $this->customerBonusPointsContext->getCustomerBonusPoints();
        }

        if ($order->getCustomer() !== $customerBonusPoints->getCustomer()) {
            return;
        }

        $order->removeAdjustmentsRecursively(AdjustmentInterface::ORDER_BONUS_POINTS_ADJUSTMENT);
        $customerBonusPoints->removeBonusPointsUsed($bonusPoints);
        $bonusPoints->setPoints(0);

        $this->persistenceManager->persist($customerBonusPoints);
        $this->persistenceManager->persist($order);
    }
}