<?php

declare(strict_types=1);

namespace spec\BitBag\SyliusBonusPointsPlugin\Validator\Constraints;

use BitBag\SyliusBonusPointsPlugin\Calculator\PerOrderPriceCalculator;
use BitBag\SyliusBonusPointsPlugin\Checker\Eligibility\BonusPointsStrategyEligibilityCheckerInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsStrategyInterface;
use BitBag\SyliusBonusPointsPlugin\Repository\BonusPointsStrategyRepositoryInterface;
use BitBag\SyliusBonusPointsPlugin\Validator\Constraints\BonusPointsApply;
use BitBag\SyliusBonusPointsPlugin\Validator\Constraints\BonusPointsApplyValidator;
use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Symfony\Component\Validator\ConstraintValidator;

final class BonusPointsApplyValidatorSpec extends ObjectBehavior
{
    function let(
        BonusPointsStrategyRepositoryInterface $bonusPointsStrategyRepository,
        BonusPointsStrategyEligibilityCheckerInterface $bonusPointsStrategyEligibilityChecker,
        CartContextInterface $cartContext
    ): void {
        $this->beConstructedWith($bonusPointsStrategyRepository, $bonusPointsStrategyEligibilityChecker, $cartContext);
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
        BonusPointsStrategyInterface $bonusPointsStrategy,
        CartContextInterface $cartContext,
        OrderInterface $order
    ): void {
        $bonusPointsApplyConstraint = new BonusPointsApply();

        $bonusPointsStrategies = [];

        $bonusPointsStrategyRepository->findActiveByCalculatorType(PerOrderPriceCalculator::TYPE)->willReturn($bonusPointsStrategies);
        $cartContext->getCart()->willReturn($order);
        $order->getItems()->willReturn(new ArrayCollection());
        
        $bonusPointsStrategyRepository->findActiveByCalculatorType(PerOrderPriceCalculator::TYPE)->shouldBeCalled();
        $cartContext->getCart()->shouldBeCalled();
        $order->getItems()->shouldBeCalled();
        $bonusPointsStrategy->getCalculatorType()->shouldNotBeCalled();

        $this->validate(99, $bonusPointsApplyConstraint);
    }
}
