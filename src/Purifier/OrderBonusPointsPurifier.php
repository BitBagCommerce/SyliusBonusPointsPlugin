<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Purifier;

use BitBag\SyliusBonusPointsPlugin\Context\CustomerBonusPointsContextInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\AdjustmentInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsInterface;
use Doctrine\Persistence\ObjectManager;

final class OrderBonusPointsPurifier implements OrderBonusPointsPurifierInterface
{
    /** @var CustomerBonusPointsContextInterface */
    private $customerBonusPointsContext;

    /** @var ObjectManager */
    private $manager;

    public function __construct(
        CustomerBonusPointsContextInterface $customerBonusPointsContext,
        ObjectManager $manager
    ) {
        $this->customerBonusPointsContext = $customerBonusPointsContext;
        $this->manager = $manager;
    }

    public function purify(BonusPointsInterface $bonusPoints): void
    {
        $order = $bonusPoints->getOrder();
        $customerBonusPoints = $this->customerBonusPointsContext->getCustomerBonusPoints();

        if ($order->getCustomer() !== $customerBonusPoints->getCustomer()) {
            return;
        }

        $order->removeAdjustmentsRecursively(AdjustmentInterface::ORDER_BONUS_POINTS_ADJUSTMENT);
        $customerBonusPoints->removeBonusPointsUsed($bonusPoints);
        $bonusPoints->setPoints(0);

        $this->manager->persist($bonusPoints);
        $this->manager->persist($customerBonusPoints);
        $this->manager->persist($order);
        $this->manager->flush();
    }
}