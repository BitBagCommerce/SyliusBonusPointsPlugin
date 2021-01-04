<?php

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
