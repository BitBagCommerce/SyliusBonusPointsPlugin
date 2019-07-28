<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

final class BonusPointsAvailability extends Constraint
{
    /** @var string */
    public $message;

    public function validatedBy(): string
    {
        return 'bitbag_bonus_points_availability';
    }

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
