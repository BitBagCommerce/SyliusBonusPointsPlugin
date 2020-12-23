<?php

declare(strict_types=1);

namespace spec\BitBag\SyliusBonusPointsPlugin\Entity;

use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\CustomerBonusPoints;
use BitBag\SyliusBonusPointsPlugin\Entity\CustomerBonusPointsInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\CustomerInterface;

final class CustomerBonusPointsSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(CustomerBonusPoints::class);
    }

    function it_implements_blacklisting_rule_interface(): void
    {
        $this->shouldHaveType(CustomerBonusPointsInterface::class);
    }

    function it_has_null_id_by_default(): void
    {
        $this->getId()->shouldReturn(null);
    }

    function it_has_no_customer_by_default(): void
    {
        $this->getCustomer()->shouldReturn(null);
    }

    function it_has_empty_bonus_points_by_default(): void
    {
        $this->getBonusPoints()->isEmpty()->shouldReturn(true);
    }

    function it_has_empty_bonus_points_used_by_default(): void
    {
        $this->getBonusPointsUsed()->isEmpty()->shouldReturn(true);
    }

    function it_sets_customer(CustomerInterface $customer): void
    {
        $this->setCustomer($customer);

        $this->getCustomer()->shouldReturn($customer);
    }

    function it_add_bonus_points(BonusPointsInterface $bonusPoints): void
    {
        $this->hasBonusPoints($bonusPoints)->shouldReturn(false);

        $this->addBonusPoints($bonusPoints);

        $this->hasBonusPoints($bonusPoints)->shouldReturn(true);
    }

    function it_add_bonus_points_used(BonusPointsInterface $bonusPointsUsed): void
    {
        $this->hasBonusPointsUsed($bonusPointsUsed)->shouldReturn(false);

        $this->addBonusPointsUsed($bonusPointsUsed);

        $this->hasBonusPointsUsed($bonusPointsUsed)->shouldReturn(true);
    }
}
