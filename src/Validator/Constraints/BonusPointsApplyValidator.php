<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Validator\Constraints;

use BitBag\SyliusBonusPointsPlugin\Calculator\PerOrderPriceCalculator;
use BitBag\SyliusBonusPointsPlugin\Checker\Eligibility\BonusPointsStrategyEligibilityCheckerInterface;
use BitBag\SyliusBonusPointsPlugin\Checker\Eligibility\BonusPointsStrategyRulesEligibilityChecker;
use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsStrategyInterface;
use BitBag\SyliusBonusPointsPlugin\Repository\BonusPointsStrategyRepositoryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Order\Context\CartContextInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class BonusPointsApplyValidator extends ConstraintValidator
{
    /** @var BonusPointsStrategyRepositoryInterface */
    private $bonusPointsStrategyRepository;

    /** @var BonusPointsStrategyRulesEligibilityChecker */
    private $bonusPointsStrategyEligibilityChecker;

    /** @var CartContextInterface */
    private $cartContext;

    public function __construct(
        BonusPointsStrategyRepositoryInterface $bonusPointsStrategyRepository,
        BonusPointsStrategyEligibilityCheckerInterface $bonusPointsStrategyEligibilityChecker,
        CartContextInterface $cartContext
    ) {
        $this->bonusPointsStrategyRepository = $bonusPointsStrategyRepository;
        $this->bonusPointsStrategyEligibilityChecker = $bonusPointsStrategyEligibilityChecker;
        $this->cartContext = $cartContext;
    }

    public function validate($bonusPoints, Constraint $constraint): void
    {
        $bonusPointsStrategies = $this->bonusPointsStrategyRepository->findActiveByCalculatorType(PerOrderPriceCalculator::TYPE);

        if (\count($bonusPointsStrategies) === 0) {
            return;
        }

        $eligibleBonusPointsStrategies = $this->extractOnlyEligibleStrategies($bonusPointsStrategies);

        if (\count($eligibleBonusPointsStrategies) === 0) {
            $this->context->buildViolation($constraint->messageInvalidOrderItem)->addViolation();
        }

        foreach ($eligibleBonusPointsStrategies as $eligibleBonusPointsStrategy) {
            if ($bonusPoints % 100 !== 0 || $bonusPoints < 100) {
                $this->context->buildViolation($constraint->messageInvalidNumber)->addViolation();

                return;
            }
        }
    }

    private function extractOnlyEligibleStrategies(array $bonusPointsStrategies): Collection
    {
        $eligibleBonusPointsStrategies = new ArrayCollection();

        $order = $this->cartContext->getCart();
        $orderItems = $order->getItems();

        /** @var BonusPointsStrategyInterface $bonusPointsStrategy */
        foreach ($bonusPointsStrategies as $bonusPointsStrategy) {
            foreach ($orderItems as $orderItem) {
                if (
                    $this->bonusPointsStrategyEligibilityChecker->isEligible($orderItem, $bonusPointsStrategy) &&
                    !$eligibleBonusPointsStrategies->contains($bonusPointsStrategy)
                ) {
                    $eligibleBonusPointsStrategies->add($bonusPointsStrategy);
                }
            }
        }

        return $eligibleBonusPointsStrategies;
    }
}
