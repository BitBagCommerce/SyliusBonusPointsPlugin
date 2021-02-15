<?php

declare(strict_types=1);

namespace spec\BitBag\SyliusBonusPointsPlugin\Purifier;

use BitBag\SyliusBonusPointsPlugin\Context\CustomerBonusPointsContextInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\AdjustmentInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\CustomerBonusPointsInterface;
use BitBag\SyliusBonusPointsPlugin\Purifier\OrderBonusPointsPurifier;
use BitBag\SyliusBonusPointsPlugin\Purifier\OrderBonusPointsPurifierInterface;
use Doctrine\Persistence\ObjectManager;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;

final class OrderBonusPointsPurifierSpec extends ObjectBehavior
{
    function let(
        CustomerBonusPointsContextInterface $customerBonusPointsContext,
        ObjectManager $manager
    ): void {
        $this->beConstructedWith($customerBonusPointsContext, $manager);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(OrderBonusPointsPurifier::class);
    }

    function it_implements_interface(): void
    {
        $this->shouldImplement(OrderBonusPointsPurifierInterface::class);
    }

    function it_purifies_bonus_points_form_order(
        CustomerBonusPointsContextInterface $customerBonusPointsContext,
        ObjectManager $manager,
        CustomerInterface $customer,
        CustomerBonusPointsInterface $customerBonusPoints,
        OrderInterface $order,
        BonusPointsInterface $bonusPoints
    ): void {
        $bonusPoints->getOrder()->willReturn($order);
        $customerBonusPointsContext->getCustomerBonusPoints()->willReturn($customerBonusPoints);
        $order->getCustomer()->willReturn($customer);
        $customerBonusPoints->getCustomer()->willReturn($customer);

        $bonusPoints->getOrder()->shouldBeCalled();
        $customerBonusPointsContext->getCustomerBonusPoints()->shouldBeCalled();
        $order->getCustomer()->shouldBeCalled();
        $customerBonusPoints->getCustomer()->shouldBeCalled();
        $order->removeAdjustmentsRecursively(AdjustmentInterface::ORDER_BONUS_POINTS_ADJUSTMENT)->shouldBeCalled();
        $customerBonusPoints->removeBonusPointsUsed($bonusPoints)->shouldBeCalled();
        $bonusPoints->setPoints(0)->shouldBeCalled();
        $manager->persist($customerBonusPoints)->shouldBeCalled();
        $manager->persist($order)->shouldBeCalled();

        $this->purify($bonusPoints);
    }

    function it_does_not_purify_bonus_points_if_it_is_not_owner_of_orede(
        CustomerBonusPointsContextInterface $customerBonusPointsContext,
        CustomerInterface $customer,
        CustomerInterface $otherCustomer,
        CustomerBonusPointsInterface $customerBonusPoints,
        OrderInterface $order,
        BonusPointsInterface $bonusPoints
    ): void {
        $bonusPoints->getOrder()->willReturn($order);
        $customerBonusPointsContext->getCustomerBonusPoints()->willReturn($customerBonusPoints);
        $order->getCustomer()->willReturn($customer);
        $customerBonusPoints->getCustomer()->willReturn($otherCustomer);

        $bonusPoints->getOrder()->shouldBeCalled();
        $customerBonusPointsContext->getCustomerBonusPoints()->shouldBeCalled();
        $order->getCustomer()->shouldBeCalled();
        $customerBonusPoints->getCustomer()->shouldBeCalled();

        $this->purify($bonusPoints);
    }
}
