<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Form\Type\Calculator;

use Sylius\Bundle\CoreBundle\Form\Type\ChannelCollectionType;
use Sylius\Component\Core\Model\ChannelInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ChannelBasedPerOrderItemPercentageConfigurationType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'entry_type' => PerOrderItemPercentageConfigurationType::class,
            'entry_options' => function (ChannelInterface $channel): array {
                $currency = $channel->getBaseCurrency();

                return [
                    'label' => $channel->getName(),
                    'currency' => null !== $currency ? $currency->getCode() : null,
                ];
            },
        ]);
    }

    public function getParent(): string
    {
        return ChannelCollectionType::class;
    }
}
