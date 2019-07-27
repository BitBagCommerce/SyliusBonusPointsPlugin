<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Calculator;

use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsStrategyInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Registry\ServiceRegistryInterface;

final class DelegatingBonusPointsStrategyCalculator implements DelegatingBonusPointsStrategyCalculatorInterface
{
    /** @var ServiceRegistryInterface */
    private $registry;

    public function __construct(ServiceRegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    public function calculate(OrderInterface $order, BonusPointsStrategyInterface $bonusPointsStrategy): int
    {
        /** @var BonusPointsStrategyCalculatorInterface $calculator */
        $calculator = $this->registry->get($bonusPointsStrategy->getCalculatorType());

        return $calculator->calculate($order, $bonusPointsStrategy->getCalculatorConfiguration());
    }
}
