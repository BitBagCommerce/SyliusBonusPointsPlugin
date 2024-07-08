<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Form\Type\Calculator;

use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Type;

final class PerOrderPriceConfigurationType extends AbstractType
{
    public function __construct(
        private readonly ChannelContextInterface $channelContext,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numberOfPointsEarnedPerOneCurrency', IntegerType::class, [
                'label' => 'bitbag_sylius_bonus_points.ui.number_of_points_earned_per_one_currency',
                'constraints' => [
                    new NotBlank(['groups' => ['bitbag_sylius_bonus_points']]),
                    new Type(['type' => 'integer', 'groups' => ['bitbag_sylius_bonus_points']]),
                    new Range(['min' => 1, 'groups' => ['bitbag_sylius_bonus_points']]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        /** @var ChannelInterface $channel */
        $channel = $this->channelContext->getChannel();
        $currency = $channel->getBaseCurrency();
        $resolver
            ->setDefaults([
                'data_class' => null,
                'currency' => null !== $currency ? $currency->getCode() : null,
            ])
            ->setRequired('currency')
            ->setAllowedTypes('currency', 'string')
        ;
    }
}
