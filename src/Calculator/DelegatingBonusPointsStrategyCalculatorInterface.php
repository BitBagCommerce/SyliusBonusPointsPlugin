<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Calculator;

use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsStrategyInterface;
use Sylius\Component\Core\Model\OrderInterface;

interface DelegatingBonusPointsStrategyCalculatorInterface
{
    public function calculate(OrderInterface $order, BonusPointsStrategyInterface $bonusPointsStrategy): int;
}
