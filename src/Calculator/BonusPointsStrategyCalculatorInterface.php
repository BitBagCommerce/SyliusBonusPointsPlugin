<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Calculator;

interface BonusPointsStrategyCalculatorInterface
{
    /**
     * @param mixed $subject
     */
    public function calculate($subject, array $configuration, int $amountToDeduct = 0): int;

    public function isPerOrderItem(): bool;

    public function getType(): string;
}
