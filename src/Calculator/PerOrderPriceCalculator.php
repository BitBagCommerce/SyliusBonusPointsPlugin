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

    /**
     * @param OrderItemInterface $subject
     */
    public function calculate($subject, array $configuration, int $amountToDeduct = 0): int
    {
        Assert::isInstanceOf($subject, OrderItemInterface::class);

        $totalPriceForOrderItems = $subject->getTotal();

        $totalFloat = $totalPriceForOrderItems / 100;
        $total = (int) (floor($totalFloat));

        $this->decimalPart += ($totalFloat - $total);

        if ($this->decimalPart >= 1) {
            $total++;

            $this->decimalPart--;
        }

        return (int) ($total * $configuration['numberOfPointsEarnedPerOneCurrency']);
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
