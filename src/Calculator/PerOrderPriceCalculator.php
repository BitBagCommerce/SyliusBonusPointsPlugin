<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

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
     * @param OrderItemInterface|mixed $subject
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
