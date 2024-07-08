<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin;

use BitBag\SyliusBonusPointsPlugin\DependencyInjection\Compiler\RegisterBonusPointsStrategyCalculatorsPass;
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
        $container->addCompilerPass(new RegisterBonusPointsStrategyCalculatorsPass());
    }
}
