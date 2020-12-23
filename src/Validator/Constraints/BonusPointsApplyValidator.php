<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Validator\Constraints;

use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsAwareInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsStrategyInterface;
use BitBag\SyliusBonusPointsPlugin\Repository\BonusPointsStrategyRepositoryInterface;
use BitBag\SyliusBonusPointsPlugin\Resolver\BonusPointsResolverInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Webmozart\Assert\Assert;

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
        $bonusPointsStrategies = $this->bonusPointsStrategyRepository->findAll();

        /** @var BonusPointsStrategyInterface $bonusPointsStrategy */
        foreach ($bonusPointsStrategies as $bonusPointsStrategy) {
            if ($bonusPointsStrategy->getCalculatorType() === 'per_order_price') {
                if ($bonusPoints % 100 !== 0 || $bonusPoints < 100) {
                    $this->context->buildViolation($constraint->message)->setTranslationDomain('validators')->addViolation();
                }
            }
        }
    }
}
