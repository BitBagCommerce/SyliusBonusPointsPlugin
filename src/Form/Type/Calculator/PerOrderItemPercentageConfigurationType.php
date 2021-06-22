<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Form\Type\Calculator;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

final class PerOrderItemPercentageConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('percentToCalculatePoints', PercentType::class, [
                'label' => 'bitbag_sylius_bonus_points.ui.percent_to_calculate_points',
                'constraints' => [
                    new NotBlank(['groups' => ['bitbag_sylius_bonus_points']]),
                    new Type(['type' => 'integer', 'groups' => ['sylius']]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'data_class' => null,
            ])
            ->setRequired('currency')
            ->setAllowedTypes('currency', 'string')
        ;
    }
}
