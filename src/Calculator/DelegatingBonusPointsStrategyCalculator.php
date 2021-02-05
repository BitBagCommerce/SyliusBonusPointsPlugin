<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Calculator;

use BitBag\SyliusBonusPointsPlugin\Checker\Eligibility\BonusPointsStrategyEligibilityCheckerInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsStrategyInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Registry\ServiceRegistryInterface;
use Webmozart\Assert\Assert;

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

    public function calculate($subject, BonusPointsStrategyInterface $bonusPointsStrategy, int $amountToDeduct = 0): int
    {
        /** @var BonusPointsStrategyCalculatorInterface $calculator */
        $calculator = $this->registry->get($bonusPointsStrategy->getCalculatorType());

        /** @var OrderItemInterface $subject */
        Assert::isInstanceOf($subject, OrderItemInterface::class);

        $isEligible = $this->bonusPointsStrategyEligibilityChecker->isEligible($subject, $bonusPointsStrategy);

        return $isEligible ? $calculator->calculate($subject, $bonusPointsStrategy->getCalculatorConfiguration(), $amountToDeduct) : 0;
    }

    public function isPerOrderItem(BonusPointsStrategyInterface $bonusPointsStrategy): bool
    {
        /** @var BonusPointsStrategyCalculatorInterface $calculator */
        $calculator = $this->registry->get($bonusPointsStrategy->getCalculatorType());

        return $calculator->isPerOrderItem();
    }
}
