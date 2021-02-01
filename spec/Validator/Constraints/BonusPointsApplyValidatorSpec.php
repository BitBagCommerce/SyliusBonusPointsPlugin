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
use Sylius\Component\Core\Model\OrderItem;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

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
        $this->shouldBeAnInstanceOf(ConstraintValidator::class);
    }

    function it_extends_constraint_validator_class(): void
    {
        $this->shouldHaveType(ConstraintValidator::class);
    }

    function it_validates(
        BonusPointsStrategyRepositoryInterface $bonusPointsStrategyRepository,
        BonusPointsStrategyInterface $bonusPointsStrategy,
        CartContextInterface $cartContext,
        OrderInterface $order,
        BonusPointsStrategyEligibilityCheckerInterface $bonusPointsStrategyEligibilityChecker,
        ExecutionContextInterface $context
    ): void {
        $bonusPointsApplyConstraint = new BonusPointsApply();
        $orderItem = new OrderItem();

        $bonusPointsStrategies = new ArrayCollection([$bonusPointsStrategy]);
        $orderItems = new ArrayCollection([$orderItem]);

        $bonusPointsStrategyRepository->findActiveByCalculatorType(PerOrderPriceCalculator::TYPE)->willReturn($bonusPointsStrategies->toArray());
        $cartContext->getCart()->willReturn($order);
        $order->getItems()->willReturn($orderItems);
        $bonusPointsStrategyEligibilityChecker->isEligible($orderItem, $bonusPointsStrategy)->willReturn(true);

        $bonusPointsStrategyRepository->findActiveByCalculatorType(PerOrderPriceCalculator::TYPE)->shouldBeCalled();
        $cartContext->getCart()->shouldBeCalled();
        $order->getItems()->shouldBeCalled();
        $bonusPointsStrategyEligibilityChecker->isEligible($orderItem, $bonusPointsStrategy)->shouldBeCalled();

        $this->initialize($context);
        $this->validate(100, $bonusPointsApplyConstraint);
    }

    function it_validates_if_it_does_not_find_strategies(BonusPointsStrategyRepositoryInterface $bonusPointsStrategyRepository): void
    {
        $bonusPointsApplyConstraint = new BonusPointsApply();

        $bonusPointsStrategyRepository->findActiveByCalculatorType(PerOrderPriceCalculator::TYPE)->willReturn([]);

        $bonusPointsStrategyRepository->findActiveByCalculatorType(PerOrderPriceCalculator::TYPE)->shouldBeCalled();

        $this->validate(100, $bonusPointsApplyConstraint);
    }

    function it_validates_if_it_does_not_have_eligible_strategies(
        BonusPointsStrategyRepositoryInterface $bonusPointsStrategyRepository,
        BonusPointsStrategyInterface $bonusPointsStrategy,
        CartContextInterface $cartContext,
        OrderInterface $order,
        BonusPointsStrategyEligibilityCheckerInterface $bonusPointsStrategyEligibilityChecker,
        ExecutionContextInterface $context,
        ConstraintViolationBuilderInterface $constraintViolationBuilder
    ): void {
        $bonusPointsApplyConstraint = new BonusPointsApply();
        $orderItem = new OrderItem();

        $bonusPointsStrategies = new ArrayCollection([$bonusPointsStrategy]);
        $orderItems = new ArrayCollection([$orderItem]);

        $bonusPointsStrategyRepository->findActiveByCalculatorType(PerOrderPriceCalculator::TYPE)->willReturn($bonusPointsStrategies->toArray());
        $cartContext->getCart()->willReturn($order);
        $order->getItems()->willReturn($orderItems);
        $bonusPointsStrategyEligibilityChecker->isEligible($orderItem, $bonusPointsStrategy)->willReturn(false);
        $context->buildViolation('bitbag_sylius_bonus_points.cart.bonus_points.cannot_use_points_for_this_taxon')->willReturn($constraintViolationBuilder);

        $bonusPointsStrategyRepository->findActiveByCalculatorType(PerOrderPriceCalculator::TYPE)->shouldBeCalled();
        $cartContext->getCart()->shouldBeCalled();
        $order->getItems()->shouldBeCalled();
        $bonusPointsStrategyEligibilityChecker->isEligible($orderItem, $bonusPointsStrategy)->shouldBeCalled();
        $context->buildViolation('bitbag_sylius_bonus_points.cart.bonus_points.cannot_use_points_for_this_taxon')->shouldBeCalled();
        $constraintViolationBuilder->addViolation()->shouldBeCalled();

        $this->initialize($context);
        $this->validate(100, $bonusPointsApplyConstraint);
    }
}
