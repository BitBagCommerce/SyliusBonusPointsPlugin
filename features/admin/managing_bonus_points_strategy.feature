@managing_bonus_points_strategy
Feature: Managing a new bonus points strategy
    In order to give possibility to managing bonus points strategies
    As an Administrator
    I want to update or delete bonus points strategy

    Background:
        Given the store operates on a single channel in "United States"
        And I am logged in as an administrator
        And the store classifies its products as "T-Shirts", "Watches", "Belts" and "Wallets"
        And the "T-Shirts" taxon has children taxon "Men" and "Women"
        And there is bonus points strategy with code "bitbag-bonus-points-strategy" and name "BitBag Bonus Points Strategy" with rule "Has taxon" with "Watches" taxon
        And the bonus points strategy "bitbag-bonus-points-strategy" admits "2" points per one currency

    @ui @javascript
    Scenario: Updating a new bonus points strategy
        When I go to update bonus points strategy "bitbag-bonus-points-strategy" page
        And I fill with "changed-bitbag-bonus-points-strategy" field "Code"
        And I fill with "Changed BitBag Bonus Points Strategy" field "Name"
        And I add the "Has taxon" rule and choose "T-shirts" taxon
        And I fill with "5" field "Number of points earned per one currency"
        And I update it
        Then I should be notified that the bonus points strategy has been successfully updated

    @ui
    Scenario: Removing bonus points strategy
        When I delete a "BitBag Bonus Points Strategy" bonus points strategy
        Then I should be notified that the bonus points strategy has been successfully deleted
        And "BitBag Bonus Points Strategy" should no longer exist in the bonus points strategy registry
