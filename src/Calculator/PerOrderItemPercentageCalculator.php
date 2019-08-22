<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Calculator;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Webmozart\Assert\Assert;

final class PerOrderItemPercentageCalculator implements BonusPointsStrategyCalculatorInterface
{
    public function calculate($subject, array $configuration, int $amountToDeduct = 0): int
    {
        /** @var OrderItemInterface $subject */
        Assert::isInstanceOf($subject, OrderItemInterface::class);

        /** @var OrderInterface $order */
        $order = $subject->getOrder();

        $configuration = $configuration[$order->getChannel()->getCode()];

        $total = $subject->getTotal();

        if ($amountToDeduct < 0) {
            $total += $amountToDeduct;
        }

        return intval(round($total * $configuration['percentToCalculatePoints']));
    }

    public function isPerOrderItem(): bool
    {
        return true;
    }

    public function getType(): string
    {
        return 'per_order_item_percentage';
    }
}
