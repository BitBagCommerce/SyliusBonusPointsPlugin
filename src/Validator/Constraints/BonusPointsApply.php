<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

final class BonusPointsApply extends Constraint
{
    /** @var string */
    public $messageInvalidNumber;

    /** @var string */
    public $messageInvalidOrderItem;

    public function validatedBy(): string
    {
        return 'bitbag_bonus_points_apply';
    }

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
