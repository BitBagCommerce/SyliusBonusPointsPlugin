<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Validator\Constraints;

use BitBag\SyliusBonusPointsPlugin\Calculator\PerOrderPriceCalculator;
use BitBag\SyliusBonusPointsPlugin\Repository\BonusPointsStrategyRepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class BonusPointsApplyValidator extends ConstraintValidator
{
    /** @var BonusPointsStrategyRepositoryInterface */
    private $bonusPointsStrategyRepository;

    public function __construct(BonusPointsStrategyRepositoryInterface $bonusPointsStrategyRepository)
    {
        $this->bonusPointsStrategyRepository = $bonusPointsStrategyRepository;
    }

    public function validate($bonusPoints, Constraint $constraint): void
    {
        if (null === $bonusPoints) {
            return;
        }

        $bonusPointsStrategies = $this->bonusPointsStrategyRepository->findActiveByCalculatorType(PerOrderPriceCalculator::TYPE);

        if (\count($bonusPointsStrategies) === 0) {
            return;
        }

        if ($bonusPoints % 100 !== 0 || $bonusPoints < 100) {
            $this->context->getViolations()->remove(0);
            $this->context->buildViolation($constraint->invalidBonusPointsValueMessage)->addViolation();

            return;
        }
    }
}
