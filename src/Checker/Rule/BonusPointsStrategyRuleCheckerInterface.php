<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Checker\Rule;

use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\ProductInterface;

interface BonusPointsStrategyRuleCheckerInterface
{
    public function isEligible(ProductInterface $product, array $configuration): bool;
}
