<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace Tests\BitBag\SyliusBonusPointsPlugin\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use Tests\BitBag\SyliusBonusPointsPlugin\Behat\Page\Shop\Checkout\CompletePageInterface;
use Webmozart\Assert\Assert;

final class CheckoutCompleteContext implements Context
{
    public function __construct(
        private CompletePageInterface $completePage,
    ) {
    }

    /**
     * @Then I should see that price of my order is equal to :price
     */
    public function iShouldSeeThatIHaveBonusPoints(string $price): void
    {
        $totalOrderPrice = $this->completePage->getOrderTotalPrice();

        Assert::same($price, $totalOrderPrice);
    }
}
