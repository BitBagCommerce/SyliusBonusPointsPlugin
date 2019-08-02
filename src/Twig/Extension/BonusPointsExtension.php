<?php

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
