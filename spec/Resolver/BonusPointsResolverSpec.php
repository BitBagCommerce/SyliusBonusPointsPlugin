<?php

declare(strict_types=1);

namespace spec\BitBag\SyliusBonusPointsPlugin\Resolver;

use BitBag\SyliusBonusPointsPlugin\Context\CustomerBonusPointsContextInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\CustomerBonusPointsInterface;
use BitBag\SyliusBonusPointsPlugin\Resolver\BonusPointsResolver;
use BitBag\SyliusBonusPointsPlugin\Resolver\BonusPointsResolverInterface;
use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;

final class BonusPointsResolverSpec extends ObjectBehavior
{
    function let(
        CustomerBonusPointsContextInterface $customerBonusPointsContext
    ): void {
        $this->beConstructedWith($customerBonusPointsContext);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(BonusPointsResolver::class);
    }

    function it_implements_bonus_points_resolver_interface(): void
    {
        $this->shouldHaveType(BonusPointsResolverInterface::class);
    }

    function it_resolves(
        CustomerBonusPointsContextInterface $customerBonusPointsContext,
        CustomerBonusPointsInterface $customerBonusPoints
    ): void {

        $customerBonusPointsContext->getCustomerBonusPoints()->willReturn($customerBonusPoints);
        $customerBonusPoints->getBonusPoints()->willReturn(new ArrayCollection());
        $customerBonusPoints->getBonusPointsUsed()->willReturn(new ArrayCollection());

        $customerBonusPointsContext->getCustomerBonusPoints()->shouldBeCalled();
        $customerBonusPoints->getBonusPoints()->shouldBeCalled();

        $this->resolveBonusPoints()->shouldReturn(0);
    }
}
