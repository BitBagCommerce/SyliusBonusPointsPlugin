<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

final class BonusPointsStrategyRuleCollectionType extends AbstractConfigurationCollectionType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefault('entry_type', BonusPointsStrategyRuleType::class);
    }
}
