<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusBonusPointsPlugin\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use Tests\BitBag\SyliusBonusPointsPlugin\Behat\Page\Shop\Checkout\CompletePageInterface;
use Webmozart\Assert\Assert;

final class CheckoutCompleteContext implements Context
{
    /** @var CompletePageInterface */
    private $completePage;

    public function __construct(CompletePageInterface $completePage)
    {
        $this->completePage = $completePage;
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
