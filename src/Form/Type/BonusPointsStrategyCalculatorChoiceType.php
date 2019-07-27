<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class BonusPointsStrategyCalculatorChoiceType extends AbstractType
{
    /** @var array */
    private $calculators;

    public function __construct(array $calculators)
    {
        $this->calculators = $calculators;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'choices' => array_flip($this->calculators),
        ]);
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
