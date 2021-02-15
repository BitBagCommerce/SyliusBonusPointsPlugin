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
     * @Given I want to resign of using bonus points and I apply zero bonus points
     */
    public function iWantToResignOfUsingBonusPoints()
    {
        $this->cartPage->applyPoints('0');
    }

    /**
     * @Given I want to resign of using bonus points and click button to reset bonus points
     */
    public function iWantToResignOfUsingBonusPointsAndClickButtonToResetBonusPoints()
    {
        $this->cartPage->resetPoints();
    }

    /**
     * @Then I should be notified that I do not have enough bonus points
     */
    public function iShouldBeNotifiedThatIDoNotHaveEnoughBonusPoints(): void
    {
        Assert::true($this->cartPage->containsErrorWithMessage(sprintf('You do not have enough bonus points.')));
    }

    /**
     * @Then I should be notified that I cannot use more bonus points than order items value
     */
    public function iShouldBeNotifiedThatICannotUseMoreBonusPointsThanOrderItemsValue(): void
    {
        Assert::true($this->cartPage->containsErrorWithMessage(sprintf('You cannot use more points than your order items value')));
    }

    /**
     * @Then I should be notified that this value should be natural number, greater than or equal to 1
     */
    public function iShouldBeNotifiedThatThisNumberMustBeNaturalNumberGreaterThanOrEqualTo(): void
    {
        Assert::true($this->cartPage->containsErrorWithMessage(sprintf('This value should be natural number, greater than or equal to 1.')));
    }
}
