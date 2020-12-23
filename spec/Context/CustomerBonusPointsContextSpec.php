<?php

declare(strict_types=1);

namespace spec\BitBag\SyliusBonusPointsPlugin\Context;

use BitBag\SyliusBonusPointsPlugin\Context\CustomerBonusPointsContext;
use BitBag\SyliusBonusPointsPlugin\Context\CustomerBonusPointsContextInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\CustomerBonusPointsInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class CustomerBonusPointsContextSpec extends ObjectBehavior
{
    function let(
        CustomerContextInterface $customerContext,
        RepositoryInterface $customerBonusPointsRepository,
        FactoryInterface $customerBonusPointsFactory
    ): void {
        $this->beConstructedWith($customerContext, $customerBonusPointsRepository, $customerBonusPointsFactory);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(CustomerBonusPointsContext::class);
    }

    function it_implements_bonus_points_resolver_interface(): void
    {
        $this->shouldHaveType(CustomerBonusPointsContextInterface::class);
    }

    function it_returns_customer_bonus_points(
        CustomerBonusPointsInterface $customerBonusPoints,
        CustomerInterface $customer,
        CustomerContextInterface $customerContext,
        RepositoryInterface $customerBonusPointsRepository
    ): void {
        $customerContext->getCustomer()->willReturn($customer);
        $customerBonusPointsRepository->findOneBy(['customer' => $customer])->willReturn($customerBonusPoints);

        $customerContext->getCustomer()->shouldBeCalled();
        $customerBonusPointsRepository->findOneBy(['customer' => $customer])->shouldBeCalled();

        $this->getCustomerBonusPoints()->shouldReturn($customerBonusPoints);
    }

    function it_creates_customer_bonus_points(
        CustomerBonusPointsInterface $customerBonusPoints,
        CustomerInterface $customer,
        CustomerContextInterface $customerContext,
        RepositoryInterface $customerBonusPointsRepository,
        FactoryInterface $customerBonusPointsFactory
    ): void {
        $customerContext->getCustomer()->willReturn($customer);
        $customerBonusPointsRepository->findOneBy(['customer' => $customer])->willReturn(null);
        $customerBonusPointsFactory->createNew()->willReturn($customerBonusPoints);

        $customerContext->getCustomer()->shouldBeCalled();
        $customerBonusPointsRepository->findOneBy(['customer' => $customer])->shouldBeCalled();
        $customerBonusPointsFactory->createNew()->shouldBeCalled();
        $customerBonusPoints->setCustomer($customer)->shouldBeCalled();
        $customerBonusPointsRepository->add($customerBonusPoints)->shouldBeCalled();

        $this->getCustomerBonusPoints()->shouldReturn($customerBonusPoints);
    }
}
