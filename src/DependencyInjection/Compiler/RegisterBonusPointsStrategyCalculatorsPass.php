<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class RegisterBonusPointsStrategyCalculatorsPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (
            !$container->hasDefinition('bitbag_sylius_bonus_points.registry.bonus_points_strategy_calculator') ||
            !$container->hasDefinition('bitbag_sylius_bonus_points.form_registry.bonus_points_strategy_calculator')
        ) {
            return;
        }

        $registry = $container->getDefinition('bitbag_sylius_bonus_points.registry.bonus_points_strategy_calculator');
        $formTypeRegistry = $container->getDefinition('bitbag_sylius_bonus_points.form_registry.bonus_points_strategy_calculator');

        $calculators = [];

        foreach ($container->findTaggedServiceIds('bitbag.bonus_points_strategy_calculator') as $id => $attributes) {
            if (!isset($attributes[0]['calculator'], $attributes[0]['label'])) {
                throw new \InvalidArgumentException('Tagged bonus points strategy calculators needs to have `calculator` and `label` attributes.');
            }

            $name = $attributes[0]['calculator'];
            $calculators[$name] = $attributes[0]['label'];

            $registry->addMethodCall('register', [$name, new Reference($id)]);

            if (isset($attributes[0]['form_type'])) {
                $formTypeRegistry->addMethodCall('add', [$name, 'default', $attributes[0]['form_type']]);
            }
        }

        $container->setParameter('bitbag.bonus_points_strategy_calculators', $calculators);
    }
}
