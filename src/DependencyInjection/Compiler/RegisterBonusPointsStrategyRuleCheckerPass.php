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

final class RegisterBonusPointsStrategyRuleCheckerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (
            !$container->has('bitbag_sylius_bonus_points.registry_bonus_points_strategy_rule_checker') ||
            !$container->has('bitbag_sylius_bonus_points.form_registry.bonus_points_strategy_rule_checker')
        ) {
            return;
        }

        $bonusPointsStrategyRuleRegistry = $container->getDefinition('bitbag_sylius_bonus_points.registry_bonus_points_strategy_rule_checker');
        $bonusPointsStrategyRuleFormTypeRegistry = $container->getDefinition('bitbag_sylius_bonus_points.form_registry.bonus_points_strategy_rule_checker');

        $bonusPointsStrategyRuleTypeToLabelMap = [];

        foreach ($container->findTaggedServiceIds('bitbag.bonus_points_strategy_rule_checker') as $id => $attributes) {
            if (!isset($attributes[0]['type'], $attributes[0]['label'], $attributes[0]['form_type'])) {
                throw new \InvalidArgumentException(sprintf('Tagged rule checker %s id needs to have `type`, `form_type`, and `label` attributes', $id));
            }

            $bonusPointsStrategyRuleTypeToLabelMap[$attributes[0]['type']] = $attributes[0]['label'];
            $bonusPointsStrategyRuleRegistry->addMethodCall('register', [$attributes[0]['type'], new Reference($id)]);
            $bonusPointsStrategyRuleFormTypeRegistry->addMethodCall('add', [$attributes[0]['type'], 'default', $attributes[0]['form_type']]);
        }

        $container->setParameter('bitbag.bonus_points_strategy_rules', $bonusPointsStrategyRuleTypeToLabelMap);
    }
}
