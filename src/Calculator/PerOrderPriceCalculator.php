<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Calculator;

use Sylius\Component\Core\Model\OrderItemInterface;
use Webmozart\Assert\Assert;

final class PerOrderPriceCalculator implements BonusPointsStrategyCalculatorInterface
{
    /** @var string */
    public const TYPE = 'per_order_price';

    /** @var float */
    private $decimalPart = 0;

    public function calculate($subject, array $configuration, int $amountToDeduct = 0): int
    {
        /** @var OrderItemInterface $subject */
        Assert::isInstanceOf($subject, OrderItemInterface::class);

        $totalPriceForOrderItems = $subject->getTotal();

        $totalFloat = $totalPriceForOrderItems / 100;
        $total = intval(floor($totalFloat));

        $this->decimalPart += ($totalFloat - $total);

        if ($this->decimalPart >= 1) {
            $total++;

            $this->decimalPart--;
        }

        return intval($total * $configuration['numberOfPointsEarnedPerOneCurrency']);
    }

    public function isPerOrderItem(): bool
    {
        return true;
    }

    public function getType(): string
    {
        return self::TYPE;
    }
}
