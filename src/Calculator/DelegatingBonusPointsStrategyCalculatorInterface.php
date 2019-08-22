<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Calculator;

use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsStrategyInterface;

interface DelegatingBonusPointsStrategyCalculatorInterface
{
    public function calculate($subject, BonusPointsStrategyInterface $bonusPointsStrategy, int $amountToDeduct): int;

    public function isPerOrderItem(BonusPointsStrategyInterface $bonusPointsStrategy): bool;
}
