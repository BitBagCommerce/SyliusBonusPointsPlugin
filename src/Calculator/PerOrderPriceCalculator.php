<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Calculator;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Webmozart\Assert\Assert;

final class PerOrderPriceCalculator implements BonusPointsStrategyCalculatorInterface
{
    public function calculate($subject, array $configuration, int $amountToDeduct = 0): int
    {
        /** @var OrderItemInterface $subject */
        Assert::isInstanceOf($subject, OrderItemInterface::class);

        /** @var OrderInterface $order */
        $order = $subject->getOrder();

        $totalPriceForOrderItems = $order->getTotal() - $order->getShippingTotal();

        $total = intval(floor($totalPriceForOrderItems / 100));

        return intval($total * $configuration['numberOfPointsEarnedPerOneCurrency']);
    }

    public function isPerOrderItem(): bool
    {
        return true;
    }

    public function getType(): string
    {
        return 'per_order_price';
    }
}
