<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Checker\Rule;

use Sylius\Component\Core\Model\OrderItemInterface;

interface BonusPointsStrategyRuleCheckerInterface
{
    public function isEligible(OrderItemInterface $orderItem, array $configuration): bool;
}
