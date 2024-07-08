<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Checker\Rule;

use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Webmozart\Assert\Assert;

final class HasTaxonRuleChecker implements BonusPointsStrategyRuleCheckerInterface
{
    public const TYPE = 'has_taxon';

    public function __construct(
        private TaxonRepositoryInterface $taxonRepository,
    ) {
    }

    public function isEligible(ProductInterface $product, array $configuration): bool
    {
        Assert::keyExists($configuration, 'taxons');

        /** @var TaxonInterface[] $taxons */
        $taxons = $this->taxonRepository->findBy(['code' => $configuration['taxons']]);

        foreach ($taxons as $taxon) {
            if ($product->hasTaxon($taxon)) {
                return true;
            }
        }

        return false;
    }
}
