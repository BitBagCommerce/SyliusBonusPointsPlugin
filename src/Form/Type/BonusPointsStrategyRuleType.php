<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;

final class BonusPointsStrategyRuleType extends AbstractConfigurableBonusPointsStrategyRuleType
{
    public function buildForm(FormBuilderInterface $builder, array $options = []): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('type', BonusPointsStrategyRuleChoiceType::class, [
                'label' => 'bitbag_sylius_bonus_points.ui.type',
                'attr' => [
                    'data-form-collection' => 'update',
                ],
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'bitbag_bonus_points_strategy_rule';
    }
}
