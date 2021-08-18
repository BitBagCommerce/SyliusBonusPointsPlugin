<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusBonusPointsPlugin\Resolver;

use BitBag\SyliusBonusPointsPlugin\Repository\BonusPointsRepositoryInterface;
use BitBag\SyliusBonusPointsPlugin\Resolver\BonusPointsResolver;
use BitBag\SyliusBonusPointsPlugin\Resolver\BonusPointsResolverInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Customer\Model\CustomerInterface;

final class BonusPointsResolverSpec extends ObjectBehavior
{
    function let(
        BonusPointsRepositoryInterface $bonusPointsRepository,
        CustomerContextInterface $customerContext
    ): void
    {
        $this->beConstructedWith($bonusPointsRepository, $customerContext);
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
        CustomerInterface $customer,
        BonusPointsRepositoryInterface $bonusPointsRepository
    ): void
    {

        $bonusPointsRepository->findAllCustomerPointsMovements($customer)->willReturn([]);

        $this->resolveBonusPoints()->shouldReturn(0);
    }
}
