<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusBonusPointsPlugin\Resolver;

use BitBag\SyliusBonusPointsPlugin\Context\CustomerBonusPointsContextInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\CustomerBonusPointsInterface;
use BitBag\SyliusBonusPointsPlugin\Resolver\BonusPointsResolver;
use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\Customer;
use Sylius\Component\Core\Model\OrderInterface;

final class BonusPointsResolverSpec extends ObjectBehavior
{
    public function let(CustomerBonusPointsContextInterface $customerBonusPointsContext): void
    {
        $this->beConstructedWith($customerBonusPointsContext);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(BonusPointsResolver::class);
    }

    public function it_resolves_bonus_points_correctly(
        CustomerBonusPointsContextInterface $customerBonusPointsContext,
        CustomerBonusPointsInterface $customerBonusPoints,
        Customer $customer,
        BonusPointsInterface $bonusPoints1,
        BonusPointsInterface $bonusPoints2,
        BonusPointsInterface $bonusPoints3,
        OrderInterface $order1,
        OrderInterface $order2
    ): void {
        $customerBonusPointsContext->getCustomerBonusPoints()->willReturn($customerBonusPoints);
        $customerBonusPoints->getCustomer()->willReturn($customer);
        $customerBonusPoints->getBonusPoints()->willReturn(new ArrayCollection([
            $bonusPoints1->getWrappedObject(),
            $bonusPoints2->getWrappedObject(),
            $bonusPoints3->getWrappedObject(),
        ]));
        $bonusPoints1->getLeftPointsFromAvailablePool(null)->willReturn(10);
        $bonusPoints1->getLeftPointsFromAvailablePool($order1)->willReturn(10);
        $bonusPoints1->getLeftPointsFromAvailablePool($order2)->willReturn(10);
        $bonusPoints1->isExpired()->willReturn(true);

        $bonusPoints2->getLeftPointsFromAvailablePool(null)->willReturn(7);
        $bonusPoints2->getLeftPointsFromAvailablePool($order1)->willReturn(7);
        $bonusPoints2->getLeftPointsFromAvailablePool($order2)->willReturn(7);
        $bonusPoints2->isExpired()->willReturn(true);

        $bonusPoints3->getLeftPointsFromAvailablePool(null)->willReturn(15);
        $bonusPoints3->getLeftPointsFromAvailablePool($order1)->willReturn(15);
        $bonusPoints3->getLeftPointsFromAvailablePool($order2)->willReturn(0);
        $bonusPoints3->isExpired()->willReturn(false);

        $bonusPoints1->getOrder()->willReturn($order1);
        $bonusPoints2->getOrder()->willReturn($order1);
        $bonusPoints3->getOrder()->willReturn($order2);

        $this->resolveBonusPoints(null, $customer)->shouldReturn(15);
        $this->resolveBonusPoints($order1, $customer)->shouldReturn(15);
        $this->resolveBonusPoints($order2, $customer)->shouldReturn(0);
    }

    public function it_returns_zero_if_customer_is_not_set(
        CustomerBonusPointsContextInterface $customerBonusPointsContext
    ): void {
        $customerBonusPointsContext->getCustomerBonusPoints()->willReturn(null);

        $this->resolveBonusPoints()->shouldReturn(0);
    }

    public function it_returns_zero_if_customer_points_are_not_set(
        CustomerBonusPointsContextInterface $customerBonusPointsContext,
        Customer $customer
    ): void {
        $customerBonusPointsContext->getCustomerBonusPoints()->willReturn(null);

        $this->resolveBonusPoints(null, $customer)->shouldReturn(0);
    }
}
