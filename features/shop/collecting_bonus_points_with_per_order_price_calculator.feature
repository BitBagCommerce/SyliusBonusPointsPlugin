@collecting_bonus_points
Feature: Collecting bonus points
    In order to collect new bonus points
    As a customer
    I want to order some products to get new bonus points

    Background:
        Given the store operates on a single channel in "United States"
        And the store classifies its products as "T-Shirts", "Watches", "Belts" and "Wallets"
        And the store has "DHL" shipping method with "$0.00" fee
        And the store allows paying "Offline"
        And there is bonus points strategy with code "bitbag-bonus-points-strategy" and name "BitBag Bonus Points Strategy" with rule "Has Taxon" with "Watches" taxon
        And the bonus points strategy "bitbag-bonus-points-strategy" admits "2" points per one currency
        And the store has a product "BitBag Watch" priced at "$60.32"
        And this product belongs to "Watches"
        And there is a customer "Francis Underwood" identified by an email "francis@underwood.com" and a password "whitehouse"
        And there is a customer "francis@underwood.com" that placed an order "#00000022"
        And the customer bought a single "BitBag Watch"
        And the customer chose "DHL" shipping method to "United States" with "Offline" payment
        Given I am logged in as an administrator
        And I view the summary of the order "#00000022"
        And I mark this order as paid
        Then I am logged in as "francis@underwood.com"
        And the store has a product "PHP Watch" priced at "$12.54"
        And this product belongs to "Watches"
        When I add this product to the cart
        Then I should see that I have "1.20" bonus points

    @ui @javascript
    Scenario: Successfully using of awarded bonus points with "per order price" calculator
        Then I want to use "1" bonus points
        Then I specified the billing address
        And I proceed with "DHL" shipping method and "Offline" payment
        Then I should be on the checkout summary step
        And I should see that price of my order is equal to "$11.54"

    @ui @javascript
    Scenario: Unsuccessfully using of awarded bonus points with "per order price" calculator
        Then I want to use "0.32" bonus points
        Then I should be notified that this number must be natural number, greater than or equal to 1