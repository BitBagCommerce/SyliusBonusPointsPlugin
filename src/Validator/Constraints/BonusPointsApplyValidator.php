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
use Sylius\Component\Order\Context\CartContextInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class BonusPointsApplyValidator extends ConstraintValidator
{
    /** @var BonusPointsStrategyRepositoryInterface */
    private $bonusPointsStrategyRepository;

    /** @var CartContextInterface */
    private $cartContext;

    public function __construct(
        BonusPointsStrategyRepositoryInterface $bonusPointsStrategyRepository,
        CartContextInterface $cartContext
    ) {
        $this->bonusPointsStrategyRepository = $bonusPointsStrategyRepository;
        $this->cartContext = $cartContext;
    }

    public function validate($bonusPoints, Constraint $constraint): void
    {
        if (null === $bonusPoints) {
            return;
        }

        if ($this->canFitBonusPointsToOrder($bonusPoints)) {
            /** @var BonusPointsApply $constraint */
            $this->context->buildViolation($constraint->exceedOrderItemsTotalMessage)->addViolation();

            return;
        }

        $bonusPointsStrategies = $this->bonusPointsStrategyRepository->findActiveByCalculatorType(PerOrderPriceCalculator::TYPE);

        if (\count($bonusPointsStrategies) === 0) {
            return;
        }

        if ($bonusPoints % 100 !== 0) {
            $this->context->getViolations()->remove(0);
            /** @var BonusPointsApply $constraint */
            $this->context->buildViolation($constraint->invalidBonusPointsValueMessage)->addViolation();

            return;
        }
    }

    private function canFitBonusPointsToOrder(int $bonusPoints): bool
    {
        $order = $this->cartContext->getCart();

        return $order->getItemsTotal() < $bonusPoints;
    }
}
