<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Twig\Extension;

use BitBag\SyliusBonusPointsPlugin\Resolver\BonusPointsResolverInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class BonusPointsExtension extends AbstractExtension
{
    /** @var BonusPointsResolverInterface */
    private $bonusPointsResolver;

    public function __construct(BonusPointsResolverInterface $bonusPointsResolver)
    {
        $this->bonusPointsResolver = $bonusPointsResolver;
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
