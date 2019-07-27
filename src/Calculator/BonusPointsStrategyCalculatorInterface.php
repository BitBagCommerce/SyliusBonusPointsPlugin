<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Calculator;

use Sylius\Component\Core\Model\OrderInterface;

interface BonusPointsStrategyCalculatorInterface
{
    public function calculate(OrderInterface $order, array $configuration): int;

    public function getType(): string;
}
