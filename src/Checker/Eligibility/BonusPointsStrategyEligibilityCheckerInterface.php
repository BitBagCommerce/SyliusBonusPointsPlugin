<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Checker\Eligibility;

use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsStrategyInterface;
use Sylius\Component\Core\Model\OrderItemInterface;

interface BonusPointsStrategyEligibilityCheckerInterface
{
    public function isEligible(OrderItemInterface $orderItem, BonusPointsStrategyInterface $bonusPointsStrategy): bool;
}
