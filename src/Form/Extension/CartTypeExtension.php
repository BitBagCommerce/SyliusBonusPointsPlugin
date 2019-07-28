<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Form\Extension;

use BitBag\SyliusBonusPointsPlugin\Context\CustomerBonusPointsContextInterface;
use Sylius\Bundle\OrderBundle\Form\Type\CartType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;

final class CartTypeExtension extends AbstractTypeExtension
{
    /** @var CustomerBonusPointsContextInterface */
    private $customerBonusPointsContext;

    public function __construct(CustomerBonusPointsContextInterface $customerBonusPointsContext)
    {
        $this->customerBonusPointsContext = $customerBonusPointsContext;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if (null === $this->customerBonusPointsContext->getCustomerBonusPoints()) {
            return;
        }

        $builder
            ->add('bonusPoints', IntegerType::class, [
                'required' => false,
            ])
        ;
    }

    public function getExtendedType(): string
    {
        return CartType::class;
    }
}
