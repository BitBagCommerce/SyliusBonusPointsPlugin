<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

final class BonusPointsApply extends Constraint
{
    /** @var string */
    public $invalidBonusPointsValueMessage = 'bitbag_sylius_bonus_points.cart.bonus_points.invalid_value';

    /** @var string */
    public $noProductsFromBonusPointsStrategyTaxonMessage = 'bitbag_sylius_bonus_points.cart.bonus_points.cannot_use_points_for_this_taxon';

    public function validatedBy(): string
    {
        return 'bitbag_bonus_points_apply';
    }

    public function getTargets(): string
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
