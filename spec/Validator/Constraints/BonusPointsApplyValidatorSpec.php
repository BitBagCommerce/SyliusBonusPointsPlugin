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
        BonusPointsStrategyEligibilityCheckerInterface $bonusPointsStrategyEligibilityChecker
    ): void {
        $bonusPointsApplyConstraint = new BonusPointsApply();
        $orderItem = new OrderItem();

        $bonusPointsStrategies = new ArrayCollection([$bonusPointsStrategy]);
        $orderItems = new ArrayCollection([$orderItem]);

        $bonusPointsStrategyRepository->findActiveByCalculatorType(PerOrderPriceCalculator::TYPE)->willReturn($bonusPointsStrategies->toArray());
        $cartContext->getCart()->willReturn($order);
        $order->getItems()->willReturn($orderItems);
        $order->getItems()->willReturn($orderItems);
        $bonusPointsStrategyEligibilityChecker->isEligible($orderItem, $bonusPointsStrategy)->willReturn(true);

        $bonusPointsStrategyRepository->findActiveByCalculatorType(PerOrderPriceCalculator::TYPE)->shouldBeCalled();
        $cartContext->getCart()->shouldBeCalled();
        $order->getItems()->shouldBeCalled();
        $bonusPointsStrategyEligibilityChecker->isEligible($orderItem, $bonusPointsStrategy)->shouldBeCalled();

        $this->validate(100, $bonusPointsApplyConstraint);
    }
}
