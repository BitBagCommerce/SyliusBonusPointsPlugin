<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusBonusPointsPlugin\Entity;

use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsStrategyInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsStrategyRule;
use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsStrategyRuleInterface;
use PhpSpec\ObjectBehavior;

final class BonusPointsStrategyRuleSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(BonusPointsStrategyRule::class);
    }

    function it_implements_blacklisting_rule_interface(): void
    {
        $this->shouldHaveType(BonusPointsStrategyRuleInterface::class);
    }

    function it_has_null_id_by_default(): void
    {
        $this->getId()->shouldReturn(null);
    }

    function it_has_no_type_by_default(): void
    {
        $this->getType()->shouldReturn(null);
    }

    function it_has_no_bonus_points_strategy_by_default(): void
    {
        $this->getBonusPointsStrategy()->shouldReturn(null);
    }

    function it_has_empty_configuration_by_default(): void
    {
        $this->getConfiguration()->shouldReturn([]);
    }

    function it_sets_type(): void
    {
        $this->setType('has_taxon');

        $this->getType()->shouldReturn('has_taxon');
    }

    function it_sets_bonus_points_strategy(BonusPointsStrategyInterface $bonusPointsStrategy): void
    {
        $this->setBonusPointsStrategy($bonusPointsStrategy);

        $this->getBonusPointsStrategy()->shouldReturn($bonusPointsStrategy);
    }

    function it_sets_configuration(): void
    {
        $configuration = ['numberOfPointsGivenPerOneCurrency' => 2];

        $this->setConfiguration($configuration);

        $this->getConfiguration()->shouldReturn($configuration);
    }
}
