<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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
