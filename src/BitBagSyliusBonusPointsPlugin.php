<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin;

use BitBag\SyliusBonusPointsPlugin\DependencyInjection\Compiler\RegisterBonusPointsStrategyRuleCheckerPass;
use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class BitBagSyliusBonusPointsPlugin extends Bundle
{
    use SyliusPluginTrait;

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new RegisterBonusPointsStrategyRuleCheckerPass());
    }
}
