<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Calculator;

use Sylius\Component\Core\Model\OrderInterface;

final class PerOrderItemPercentageCalculator implements BonusPointsStrategyCalculatorInterface
{
    public function calculate(OrderInterface $order, array $configuration): int
    {
        // TODO: Implement calculate() method.
    }

    public function getType(): string
    {
        return 'per_order_item_percentage';
    }
}
