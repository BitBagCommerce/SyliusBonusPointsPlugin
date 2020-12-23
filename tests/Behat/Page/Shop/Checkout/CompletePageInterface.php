<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusBonusPointsPlugin\Behat\Page\Shop\Checkout;

use Sylius\Behat\Page\Shop\Checkout\CompletePageInterface as BaseCompletePageInterface;

interface CompletePageInterface extends BaseCompletePageInterface
{
    public function getOrderTotalPrice(): string;
}
