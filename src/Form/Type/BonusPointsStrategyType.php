<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Form\Type;

use BitBag\SyliusBonusPointsPlugin\Calculator\BonusPointsStrategyCalculatorInterface;
use Sylius\Bundle\ResourceBundle\Form\EventSubscriber\AddCodeFormSubscriber;
use Sylius\Bundle\ResourceBundle\Form\Registry\FormTypeRegistryInterface;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Component\Registry\ServiceRegistryInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

final class BonusPointsStrategyType extends AbstractResourceType
{
    public function __construct(
        string $dataClass,
        array $validationGroups,
        private ServiceRegistryInterface $calculatorRegistry,
        private FormTypeRegistryInterface $formTypeRegistry,
    ) {
        parent::__construct($dataClass, $validationGroups);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'bitbag_sylius_bonus_points.ui.name',
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'bitbag_sylius_bonus_points.ui.enabled',
            ])
            ->add('isDeductBonusPoints', CheckboxType::class, [
                'label' => 'bitbag_sylius_bonus_points.ui.is_deduct_bonus_points',
            ])
            ->add('rules', BonusPointsStrategyRuleCollectionType::class, [
                'label' => 'bitbag_sylius_bonus_points.ui.rules',
            ])
            ->add('calculatorType', BonusPointsStrategyCalculatorChoiceType::class, [
                'label' => 'bitbag_sylius_bonus_points.ui.calculator_type',
            ])
            ->addEventSubscriber(new AddCodeFormSubscriber())
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event): void {
                $data = $event->getData();

                if (null === $data || null === $data->getId()) {
                    return;
                }

                $this->addConfigurationField($event->getForm(), $data->getCalculatorType());
            })
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event): void {
                $data = $event->getData();

                if (null === $data || !array_key_exists('calculatorType', $data)) {
                    return;
                }

                $this->addConfigurationField($event->getForm(), $data['calculatorType']);
            })
        ;

        $prototypes = [];

        foreach ($this->calculatorRegistry->all() as $name => $calculator) {
            /** @var BonusPointsStrategyCalculatorInterface $calculator */
            $calculatorType = $calculator->getType();

            if (!$this->formTypeRegistry->has($calculatorType, 'default')) {
                continue;
            }

            $form = $builder->create('calculatorConfiguration', $this->formTypeRegistry->get($calculatorType, 'default'));

            $prototypes['calculators'][$name] = $form->getForm();
        }

        $builder->setAttribute('prototypes', $prototypes);
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['prototypes'] = [];

        foreach ($form->getConfig()->getAttribute('prototypes') as $group => $prototypes) {
            foreach ($prototypes as $type => $prototype) {
                $view->vars['prototypes'][$group . '_' . $type] = $prototype->createView($view);
            }
        }
    }

    private function addConfigurationField(FormInterface $form, string $calculatorName): void
    {
        /** @var BonusPointsStrategyCalculatorInterface $calculator */
        $calculator = $this->calculatorRegistry->get($calculatorName);

        $calculatorType = $calculator->getType();

        if (!$this->formTypeRegistry->has($calculatorType, 'default')) {
            return;
        }

        $form->add('calculatorConfiguration', $this->formTypeRegistry->get($calculatorType, 'default'));
    }
}
