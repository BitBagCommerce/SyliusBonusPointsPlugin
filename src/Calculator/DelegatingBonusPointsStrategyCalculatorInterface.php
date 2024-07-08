<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Calculator;

use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsStrategyInterface;
use Sylius\Component\Core\Model\OrderItemInterface;

interface DelegatingBonusPointsStrategyCalculatorInterface
{
    public function calculate(OrderItemInterface $subject, BonusPointsStrategyInterface $bonusPointsStrategy, int $amountToDeduct): int;

    public function isPerOrderItem(BonusPointsStrategyInterface $bonusPointsStrategy): bool;
}
