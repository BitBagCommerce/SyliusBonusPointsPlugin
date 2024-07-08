<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Form\Type;

use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsStrategyRuleInterface;
use Sylius\Bundle\ResourceBundle\Form\Registry\FormTypeRegistryInterface;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractConfigurableBonusPointsStrategyRuleType extends AbstractResourceType
{
    public function __construct(
        string $dataClass,
        array $validationGroups = [],
        private readonly FormTypeRegistryInterface $formTypeRegistry,
    ) {
        parent::__construct($dataClass, $validationGroups);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event): void {
                $type = $this->getRegistryIdentifier($event->getForm(), $event->getData());
                if (null === $type) {
                    return;
                }
                $configurationType = (string) $this->formTypeRegistry->get($type, 'default');
                $this->addConfigurationFields($event->getForm(), $configurationType);
            })
            ->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event): void {
                $type = $this->getRegistryIdentifier($event->getForm(), $event->getData());
                if (null === $type) {
                    return;
                }

                $event->getForm()->get('type')->setData($type);
            })
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event): void {
                $data = $event->getData();

                if (!isset($data['type'])) {
                    return;
                }

                $configurationType = (string) $this->formTypeRegistry->get($data['type'], 'default');
                $this->addConfigurationFields($event->getForm(), $configurationType);
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('configuration_type', null)
            ->setAllowedTypes('configuration_type', ['string', 'null'])
        ;
    }

    protected function addConfigurationFields(FormInterface $form, string $configurationType): void
    {
        $form->add('configuration', $configurationType, [
            'label' => false,
        ]);
    }

    /**
     * @param mixed|null $data
     */
    protected function getRegistryIdentifier(FormInterface $form, $data = null): ?string
    {
        if ($data instanceof BonusPointsStrategyRuleInterface && null !== $data->getType()) {
            return $data->getType();
        }

        if ($form->getConfig()->hasOption('configuration_type')) {
            return $form->getConfig()->getOption('configuration_type');
        }

        return null;
    }
}
