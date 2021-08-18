<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusBonusPointsPlugin\Checker\Eligibility;

use BitBag\SyliusBonusPointsPlugin\Checker\Eligibility\BonusPointsStrategyEligibilityCheckerInterface;
use BitBag\SyliusBonusPointsPlugin\Checker\Eligibility\BonusPointsStrategyRulesEligibilityChecker;
use BitBag\SyliusBonusPointsPlugin\Checker\Rule\BonusPointsStrategyRuleCheckerInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsStrategyInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsStrategyRuleInterface;
use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Registry\ServiceRegistryInterface;

final class BonusPointsStrategyRulesEligibilityCheckerSpec extends ObjectBehavior
{
    function let(ServiceRegistryInterface $ruleRegistry): void
    {
        $this->beConstructedWith($ruleRegistry);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(BonusPointsStrategyRulesEligibilityChecker::class);
    }

    function it_implements_bonus_points_resolver_interface(): void
    {
        $this->shouldHaveType(BonusPointsStrategyEligibilityCheckerInterface::class);
    }

    function it_returns_false(
        OrderItemInterface $orderItem,
        ProductInterface $product,
        BonusPointsStrategyInterface $bonusPointsStrategy,
        ServiceRegistryInterface $ruleRegistry,
        BonusPointsStrategyRuleInterface $bonusPointsStrategyRule,
        BonusPointsStrategyRuleCheckerInterface $checker
    ): void {
        $ruleConfiguration = ['taxons' => ['t-shirts']];

        $bonusPointsStrategy->hasRules()->willReturn(true);
        $bonusPointsStrategy->getRules()->willReturn(new ArrayCollection([$bonusPointsStrategyRule->getWrappedObject()]));
        $bonusPointsStrategyRule->getType()->willReturn('has_taxon');
        $ruleRegistry->get('has_taxon')->willReturn($checker);
        $bonusPointsStrategyRule->getConfiguration()->willReturn($ruleConfiguration);
        $checker->isEligible($product, $ruleConfiguration)->willReturn(false);

        $bonusPointsStrategy->hasRules()->shouldBeCalled();
        $bonusPointsStrategy->getRules()->shouldBeCalled();
        $bonusPointsStrategyRule->getType()->shouldBeCalled();
        $ruleRegistry->get('has_taxon')->shouldBeCalled();
        $bonusPointsStrategyRule->getConfiguration()->shouldBeCalled();
        $checker->isEligible($product, $ruleConfiguration)->shouldBeCalled();

        $this->isEligible($product, $bonusPointsStrategy)->shouldReturn(false);
    }

    function it_returns_true(
        ProductInterface $product,
        BonusPointsStrategyInterface $bonusPointsStrategy
    ): void {
        $bonusPointsStrategy->hasRules()->willReturn(true);
        $bonusPointsStrategy->getRules()->willReturn(new ArrayCollection());

        $bonusPointsStrategy->hasRules()->shouldBeCalled();
        $bonusPointsStrategy->getRules()->shouldBeCalled();

        $this->isEligible($product, $bonusPointsStrategy)->shouldReturn(true);
    }
}
