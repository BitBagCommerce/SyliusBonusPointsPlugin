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
    function let(BonusPointsStrategyRepositoryInterface $bonusPointsStrategyRepository, CartContextInterface $cartContext): void
    {
        $this->beConstructedWith($bonusPointsStrategyRepository, $cartContext);
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
        ConstraintViolationListInterface $constraintViolationList,
        ExecutionContextInterface $context,
        ConstraintViolationBuilderInterface $constraintViolationBuilder,
        CartContextInterface $cartContext,
        OrderInterface $order
    ): void {
        $bonusPointsApplyConstraint = new BonusPointsApply();

        $cartContext->getCart()->willReturn($order);
        $order->getItemsTotal()->willReturn(2682);
        $bonusPointsStrategyRepository->findActiveByCalculatorType(PerOrderPriceCalculator::TYPE)->willReturn([$bonusPointsStrategy]);
        $context->getViolations()->willReturn($constraintViolationList);
        $context->buildViolation('bitbag_sylius_bonus_points.cart.bonus_points.invalid_value')->willReturn($constraintViolationBuilder);

        $cartContext->getCart()->shouldBeCalled();
        $order->getItemsTotal()->shouldBeCalled();
        $bonusPointsStrategyRepository->findActiveByCalculatorType(PerOrderPriceCalculator::TYPE)->shouldBeCalled();
        $context->getViolations()->shouldBeCalled();
        $constraintViolationList->remove(0)->shouldBeCalled();
        $context->buildViolation('bitbag_sylius_bonus_points.cart.bonus_points.invalid_value')->shouldBeCalled();
        $constraintViolationBuilder->addViolation()->shouldBeCalled();

        $this->initialize($context);
        $this->validate(232, $bonusPointsApplyConstraint)->shouldReturn(null);
    }

    function it_validates_if_it_does_not_find_strategies(
        BonusPointsStrategyRepositoryInterface $bonusPointsStrategyRepository,
        CartContextInterface $cartContext,
        OrderInterface $order
    ): void {
        $bonusPointsApplyConstraint = new BonusPointsApply();

        $cartContext->getCart()->willReturn($order);
        $order->getItemsTotal()->willReturn(2682);
        $bonusPointsStrategyRepository->findActiveByCalculatorType(PerOrderPriceCalculator::TYPE)->willReturn([]);

        $cartContext->getCart()->shouldBeCalled();
        $order->getItemsTotal()->shouldBeCalled();
        $bonusPointsStrategyRepository->findActiveByCalculatorType(PerOrderPriceCalculator::TYPE)->shouldBeCalled();

        $this->validate(100, $bonusPointsApplyConstraint)->shouldReturn(null);
    }

    function it_validates_if_bonus_points_are_null(): void
    {
        $bonusPointsApplyConstraint = new BonusPointsApply();

        $this->validate(null, $bonusPointsApplyConstraint)->shouldReturn(null);
    }

    function it_validates_if_bonus_points_are_greater_than_order_items_total(
        CartContextInterface $cartContext,
        OrderInterface $order,
        ExecutionContextInterface $context,
        ConstraintViolationBuilderInterface $constraintViolationBuilder
    ): void {
        $bonusPointsApplyConstraint = new BonusPointsApply();

        $cartContext->getCart()->willReturn($order);
        $order->getItemsTotal()->willReturn(2682);
        $context->buildViolation('bitbag_sylius_bonus_points.cart.bonus_points.exceed_order_items_total')->willReturn($constraintViolationBuilder);

        $cartContext->getCart()->shouldBeCalled();
        $order->getItemsTotal()->shouldBeCalled();
        $context->buildViolation('bitbag_sylius_bonus_points.cart.bonus_points.exceed_order_items_total')->shouldBeCalled();
        $constraintViolationBuilder->addViolation()->shouldBeCalled();

        $this->initialize($context);
        $this->validate(3000, $bonusPointsApplyConstraint)->shouldReturn(null);
    }
}
