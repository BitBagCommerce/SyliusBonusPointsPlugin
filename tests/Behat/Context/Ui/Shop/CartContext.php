<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusBonusPointsPlugin\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use Sylius\Behat\Client\ResponseCheckerInterface;
use Tests\BitBag\SyliusBonusPointsPlugin\Behat\Page\Shop\Cart\CartPageInterface;
use Webmozart\Assert\Assert;

final class CartContext implements Context
{
    /** @var CartPageInterface */
    private $cartPage;

    public function __construct(CartPageInterface $cartPage)
    {
        $this->cartPage = $cartPage;
    }

    /**
     * @Then I should see that I have :points bonus points
     */
    public function iShouldSeeThatIHaveBonusPoints(string $points): void
    {
        $availableBonusPoints = $this->cartPage->getNumberOfAvailableBonusPoints();

        Assert::same($availableBonusPoints, $points);
    }

    /**
     * @Then I want to use :points bonus points
     */
    public function iWantToUseBonusPoint(string $points): void
    {
        $this->cartPage->applyPoints($points);
    }

    /**
     * @Then I should be notified that I do not have enough bonus points
     */
    public function iShouldBeNotifiedThatIDoNotHaveEnoughBonusPoints(): void
    {
        Assert::true($this->cartPage->containsErrorWithMessage(sprintf('You do not have enough bonus points.')));
    }

    /**
     * @Then I should be notified that this number must be natural number, greater than or equal to 1
     */
    public function iShouldBeNotifiedThatThisNumberMustBeNaturalNumberGreaterThanOrEqualTo(): void
    {
        Assert::true($this->cartPage->containsErrorWithMessage(sprintf('This number must be natural number, greater than or equal to 1.')));
    }
}
