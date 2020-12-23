<?php

declare(strict_types=1);

namespace spec\BitBag\SyliusBonusPointsPlugin\Checker\Rule;

use BitBag\SyliusBonusPointsPlugin\Checker\Rule\BonusPointsStrategyRuleCheckerInterface;
use BitBag\SyliusBonusPointsPlugin\Checker\Rule\HasTaxonRuleChecker;
use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;

final class HasTaxonRuleCheckerSpec extends ObjectBehavior
{
    function let(TaxonRepositoryInterface $taxonRepository): void
    {
        $this->beConstructedWith($taxonRepository);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(HasTaxonRuleChecker::class);
    }

    function it_implements_bonus_points_resolver_interface(): void
    {
        $this->shouldHaveType(BonusPointsStrategyRuleCheckerInterface::class);
    }

    function it_checks(
        OrderItemInterface $orderItem,
        TaxonRepositoryInterface $taxonRepository,
        ProductInterface $product,
        TaxonInterface $taxon
    ): void {
        $taxons = [$taxon];

        $configuration = ['taxons' => ['t-shirts']];

        $orderItem->getProduct()->willReturn($product);
        $taxonRepository->findBy(['code' => $configuration['taxons']])->willReturn($taxons);
        $product->hasTaxon($taxon)->willReturn(true);

        $orderItem->getProduct()->shouldBeCalled();
        $taxonRepository->findBy(['code' => $configuration['taxons']])->shouldBeCalled();
        $product->hasTaxon(Argument::type(TaxonInterface::class))->shouldBeCalled();

        $this->isEligible($orderItem, $configuration)->shouldReturn(true);
    }
}
