<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Checker\Eligibility;

use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsStrategyInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\ProductInterface;

interface BonusPointsStrategyEligibilityCheckerInterface
{
    public function isEligible(ProductInterface $product, BonusPointsStrategyInterface $bonusPointsStrategy): bool;
}
