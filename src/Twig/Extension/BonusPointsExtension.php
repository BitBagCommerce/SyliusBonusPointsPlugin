<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Twig\Extension;

use BitBag\SyliusBonusPointsPlugin\Resolver\BonusPointsResolverInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class BonusPointsExtension extends AbstractExtension
{
    public function __construct(
        private readonly BonusPointsResolverInterface $bonusPointsResolver,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('bitbag_active_bonus_points', [$this, 'getActiveBonusPoints']),
        ];
    }

    public function getActiveBonusPoints(): int
    {
        return $this->bonusPointsResolver->resolveBonusPoints();
    }
}
