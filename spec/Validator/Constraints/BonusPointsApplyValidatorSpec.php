<?php

declare(strict_types=1);

namespace spec\BitBag\SyliusBonusPointsPlugin\Validator\Constraints;

use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsStrategyInterface;
use BitBag\SyliusBonusPointsPlugin\Repository\BonusPointsStrategyRepositoryInterface;
use BitBag\SyliusBonusPointsPlugin\Validator\Constraints\BonusPointsApply;
use BitBag\SyliusBonusPointsPlugin\Validator\Constraints\BonusPointsApplyValidator;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Validator\ConstraintValidator;

final class BonusPointsApplyValidatorSpec extends ObjectBehavior
{
    function let(
        BonusPointsStrategyRepositoryInterface $bonusPointsStrategyRepository
    ): void {
        $this->beConstructedWith($bonusPointsStrategyRepository);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(BonusPointsApplyValidator::class);
    }

    function it_extends_constraint_validator_class(): void
    {
        $this->shouldHaveType(ConstraintValidator::class);
    }

    function it_validates(
        BonusPointsStrategyRepositoryInterface $bonusPointsStrategyRepository,
        BonusPointsStrategyInterface $bonusPointsStrategy
    ): void {
        $bonusPointsApplyConstraint = new BonusPointsApply();

        $bonusPointsStrategies = [];

        $bonusPointsStrategyRepository->findAll()->willReturn($bonusPointsStrategies);

        $bonusPointsStrategyRepository->findAll()->shouldBeCalled();
        $bonusPointsStrategy->getCalculatorType()->shouldNotBeCalled();

        $this->validate(99, $bonusPointsApplyConstraint);
    }
}
