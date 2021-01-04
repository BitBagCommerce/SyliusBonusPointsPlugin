@managing_bonus_points_strategy
Feature: Adding a new bonus points strategy
    In order to give possibility to collecting bonus points
    As an Administrator
    I want to add a new bonus points strategy

    Background:
        Given the store operates on a single channel in "United States"
        And I am logged in as an administrator
        And the store classifies its products as "T-Shirts", "Watches", "Belts" and "Wallets"
        And the "T-Shirts" taxon has children taxon "Men" and "Women"

    @ui @javascript
    Scenario: Adding a new bonus points strategy with "Per order price" calculator
        When I go to the create bonus points strategy page
        And I fill with "first-bitbag-bonus-points-strategy" field "Code"
        And I name it "First BitBag Bonus Points Strategy"
        And I enable it
        And I add the "Has taxon" rule and choose "T-shirts" taxon
        And I select "Per order price" as calculator
        And I check that it is a deduct bonus points
        And I fill with "2" field "Number of points earned per one currency"
        And I add it
        Then I should be notified that the bonus points strategy has been created
        And the "first-bitbag-bonus-points-strategy" should appear in the registry

    @ui @javascript
    Scenario: Adding a new bonus points strategy with "Per order item percentage" calculator
        When I go to the create bonus points strategy page
        And I fill with "second-bitbag-bonus-points-strategy" field "Code"
        And I name it "Second BitBag Bonus Points Strategy"
        And I enable it
        And I add the "Has taxon" rule and choose "Watches" taxon
        And I select "Per order item percentage" as calculator
        And I check that it is a deduct bonus points
        And I fill with "10" field "Percent to calculate points"
        And I add it
        Then I should be notified that the bonus points strategy has been created
        And the "second-bitbag-bonus-points-strategy" should appear in the registry