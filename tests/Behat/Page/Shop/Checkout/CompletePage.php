<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusBonusPointsPlugin\Behat\Page\Shop\Checkout;

use Sylius\Behat\Page\Shop\Checkout\CompletePage as BaseCompletePage;

class CompletePage extends BaseCompletePage implements CompletePageInterface
{
    public function getOrderTotalPrice(): string
    {
        $orderTotalPrice = array_reverse(explode(' ', $this->getDocument()->findById('total')->getText()));

        return $orderTotalPrice[0];
    }
}
