<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusBonusPointsPlugin\Validator\Constraints;

use BitBag\SyliusBonusPointsPlugin\Resolver\BonusPointsResolverInterface;
use BitBag\SyliusBonusPointsPlugin\Validator\Constraints\BonusPointsAvailability;
use BitBag\SyliusBonusPointsPlugin\Validator\Constraints\BonusPointsAvailabilityValidator;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Validator\ConstraintValidator;
use Tests\BitBag\SyliusBonusPointsPlugin\Entity\Order;

final class BonusPointsAvailabilityValidatorSpec extends ObjectBehavior
{
    public function let(
        BonusPointsResolverInterface $bonusPointsResolver,
        RepositoryInterface $bonusPointsRepository
    ): void {
        $this->beConstructedWith($bonusPointsResolver, $bonusPointsRepository);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(BonusPointsAvailabilityValidator::class);
    }

    public function it_extends_constraint_validator_class(): void
    {
        $this->shouldHaveType(ConstraintValidator::class);
    }

    public function it_validates(
        Order $order,
        RepositoryInterface $bonusPointsRepository
    ): void {
        $bonusPointsAvailabilityConstraint = new BonusPointsAvailability();

        $order->getBonusPoints()->willReturn(null);

        $bonusPointsRepository->findBy(['order' => $order, 'isUsed' => true])->willReturn([]);
        $bonusPointsRepository->findBy(['order' => $order, 'isUsed' => true])->shouldBeCalled();
        $order->getBonusPoints()->shouldBeCalled();
        $order->getBonusPoints()->shouldBeCalled();

        $this->validate($order, $bonusPointsAvailabilityConstraint);
    }
}
