<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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
use Webmozart\Assert\Assert;

abstract class AbstractConfigurableBonusPointsStrategyRuleType extends AbstractResourceType
{
    public function __construct(string $dataClass, array $validationGroups, private FormTypeRegistryInterface $formTypeRegistry)
    {
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
                /** @var array $data */
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
            ->setAllowedTypes('configuration_type', ['string', 'null']);
    }

    protected function addConfigurationFields(FormInterface $form, string $configurationType): void
    {
        $form->add('configuration', $configurationType, [
            'label' => false,
        ]);
    }

    protected function getRegistryIdentifier(FormInterface $form, mixed $data = null): ?string
    {
        if ($data instanceof BonusPointsStrategyRuleInterface && null !== $data->getType()) {
            return $data->getType();
        }

        if ($form->getConfig()->hasOption('configuration_type')) {
            if (null === $form->getConfig()->getOption('configuration_type')) {
                return null;
            }
            Assert::string($form->getConfig()->getOption('configuration_type'));

            return $form->getConfig()->getOption('configuration_type');
        }

        return null;
    }
}
