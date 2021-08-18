<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

final class BonusPointsApply extends Constraint
{
    /** @var string */
    public $invalidBonusPointsValueMessage = 'bitbag_sylius_bonus_points.cart.bonus_points.invalid_value';

    /** @var string */
    public $exceedOrderItemsTotalMessage = 'bitbag_sylius_bonus_points.cart.bonus_points.exceed_order_items_total';

    public function validatedBy(): string
    {
        return 'bitbag_bonus_points_apply';
    }

    public function getTargets(): string
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
