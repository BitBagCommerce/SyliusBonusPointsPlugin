<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Calculator;

use BitBag\SyliusBonusPointsPlugin\Checker\Eligibility\BonusPointsStrategyEligibilityCheckerInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsStrategyInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Registry\ServiceRegistryInterface;

final class DelegatingBonusPointsStrategyCalculator implements DelegatingBonusPointsStrategyCalculatorInterface
{
    public function __construct(
        private readonly ServiceRegistryInterface $registry,
        private readonly BonusPointsStrategyEligibilityCheckerInterface $bonusPointsStrategyEligibilityChecker,
    ) {
    }

    public function calculate(OrderItemInterface $subject, BonusPointsStrategyInterface $bonusPointsStrategy, int $amountToDeduct = 0): int
    {
        /** @var BonusPointsStrategyCalculatorInterface $calculator */
        $calculator = $this->registry->get((string) $bonusPointsStrategy->getCalculatorType());

        $product = $subject->getProduct();

        if (null === $product) {
            return 0;
        }

        $isEligible = $this->bonusPointsStrategyEligibilityChecker->isEligible($product, $bonusPointsStrategy);

        return $isEligible ? $calculator->calculate($subject, $bonusPointsStrategy->getCalculatorConfiguration(), $amountToDeduct) : 0;
    }

    public function isPerOrderItem(BonusPointsStrategyInterface $bonusPointsStrategy): bool
    {
        /** @var BonusPointsStrategyCalculatorInterface $calculator */
        $calculator = $this->registry->get((string) $bonusPointsStrategy->getCalculatorType());

        return $calculator->isPerOrderItem();
    }
}
