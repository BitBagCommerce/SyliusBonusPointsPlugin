<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\EventSubscriber\AddCodeFormSubscriber;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class BonusPointsStrategyType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'bitbag_sylius_bonus_points.ui.name',
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'bitbag_sylius_bonus_points.ui.enabled',
            ])
            ->add('rules', BonusPointsStrategyRuleCollectionType::class, [
                'label' => 'bitbag_sylius_bonus_points.ui.rules',
            ])
            ->addEventSubscriber(new AddCodeFormSubscriber())
        ;
    }
}
