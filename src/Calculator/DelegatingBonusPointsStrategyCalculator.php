<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Calculator;

use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsStrategyInterface;
use Sylius\Component\Registry\ServiceRegistryInterface;

final class DelegatingBonusPointsStrategyCalculator implements DelegatingBonusPointsStrategyCalculatorInterface
{
    /** @var ServiceRegistryInterface */
    private $registry;

    public function __construct(ServiceRegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    public function calculate($subject, BonusPointsStrategyInterface $bonusPointsStrategy, int $amountToDeduct = 0): int
    {
        /** @var BonusPointsStrategyCalculatorInterface $calculator */
        $calculator = $this->registry->get($bonusPointsStrategy->getCalculatorType());

        return $calculator->calculate($subject, $bonusPointsStrategy->getCalculatorConfiguration(), $amountToDeduct);
    }

    public function isPerOrderItem(BonusPointsStrategyInterface $bonusPointsStrategy): bool
    {
        /** @var BonusPointsStrategyCalculatorInterface $calculator */
        $calculator = $this->registry->get($bonusPointsStrategy->getCalculatorType());

        return $calculator->isPerOrderItem();
    }
}
