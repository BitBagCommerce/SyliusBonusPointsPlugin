<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Calculator;

use BitBag\SyliusBonusPointsPlugin\Checker\Eligibility\BonusPointsStrategyEligibilityCheckerInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsStrategyInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Registry\ServiceRegistryInterface;

final class DelegatingBonusPointsStrategyCalculator implements DelegatingBonusPointsStrategyCalculatorInterface
{
    /** @var ServiceRegistryInterface */
    private $registry;

    /** @var BonusPointsStrategyEligibilityCheckerInterface */
    private $bonusPointsStrategyEligibilityChecker;

    public function __construct(
        ServiceRegistryInterface $registry,
        BonusPointsStrategyEligibilityCheckerInterface $bonusPointsStrategyEligibilityChecker
    ) {
        $this->registry = $registry;
        $this->bonusPointsStrategyEligibilityChecker = $bonusPointsStrategyEligibilityChecker;
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
