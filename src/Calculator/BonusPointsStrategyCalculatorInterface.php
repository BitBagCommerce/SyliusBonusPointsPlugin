<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Calculator;

interface BonusPointsStrategyCalculatorInterface
{
    public function calculate($subject, array $configuration): int;

    public function isPerOrderItem(): bool;

    public function getType(): string;
}
