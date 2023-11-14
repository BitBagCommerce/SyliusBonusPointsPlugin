<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Validator\Constraints;

use BitBag\SyliusBonusPointsPlugin\Calculator\PerOrderPriceCalculator;
use BitBag\SyliusBonusPointsPlugin\Repository\BonusPointsStrategyRepositoryInterface;
use function count;
use Sylius\Component\Order\Context\CartContextInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class BonusPointsApplyValidator extends ConstraintValidator
{
    public function __construct(
        private BonusPointsStrategyRepositoryInterface $bonusPointsStrategyRepository,
        private CartContextInterface $cartContext,
    ) {
    }

    public function validate(mixed $bonusPoints, Constraint $constraint): void
    {
        if (false === is_int($bonusPoints)) {
            return;
        }

        if ($this->canFitBonusPointsToOrder($bonusPoints)) {
            $this->context->buildViolation($constraint->exceedOrderItemsTotalMessage)->addViolation();

            return;
        }

        $bonusPointsStrategies = $this->bonusPointsStrategyRepository->findActiveByCalculatorType(PerOrderPriceCalculator::TYPE);

        if (0 === count($bonusPointsStrategies)) {
            return;
        }

        if (0 !== $bonusPoints % 100) {
            $this->context->getViolations()->remove(0);
            $this->context->buildViolation($constraint->invalidBonusPointsValueMessage)->addViolation();
        }
    }

    private function canFitBonusPointsToOrder(int $bonusPoints): bool
    {
        $order = $this->cartContext->getCart();

        return $order->getItemsTotal() < $bonusPoints;
    }
}
