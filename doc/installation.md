
1. Require plugin with composer:

    ```bash
    composer require bitbag/bonus-points-plugin
    ```

1. Add plugin dependencies to your `config/bundles.php` file:

    ```php
        return [
         ...
        
            BitBag\SyliusBonusPointsPlugin\BitBagSyliusBonusPointsPlugin::class => ['all' => true],
        ];
    ```

1. Import required config by adding  `config/packages/bitbag_sylius_bonus_points_plugin.yaml` file:

    ```yaml
    # config/packages/bitbag_sylius_bonus_points_plugin.yaml
    
    imports:
       - { resource: "@BitBagSyliusBonusPointsPlugin/Resources/config/config.yml" }
    ```    

1. Import routing in your `config/routes.yaml` file:

    ```yaml
    
    # config/routes.yaml
    ...
    
    bitbag_sylius_bonus_points_plugin:
        resource: "@BitBagSyliusBonusPointsPlugin/Resources/config/routing.yml"
    ```

1. Extend `Order`(including Doctrine mapping):

    ```php
    <?php 
   
   declare(strict_types=1);
    
    namespace App\Entity\Order;
    
    use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsAwareInterface;
    use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsAwareTrait;
    use Sylius\Component\Core\Model\Order as BaseOrder;

    class Order extends BaseOrder implements BonusPointsAwareInterface
    {
        use BonusPointsAwareTrait;
    }
    ```

   Mapping (XML):

   ```xml
   <?xml version="1.0" encoding="UTF-8"?>
   <doctrine-mapping xmlns="http://doctrine-project.org/schemas/orm/doctrine-mapping"
                     xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                     xsi:schemaLocation="http://doctrine-project.org/schemas/orm/doctrine-mapping
                                         http://doctrine-project.org/schemas/orm/doctrine-mapping.xsd"
   >
         <entity repository-class="App\Doctrine\ORM\OrderRepository" name="App\Entity\Order\Order" table="sylius_order">
               <field name="bonusPoints" type="integer" nullable="true" />
         </entity>
   </doctrine-mapping>
   ```

1. Customize shop templates to include bonus points. For starter You may want copy to templates directory everything from plugin's tests/Application/templates

1. Finish the installation by updating the database schema and installing assets:

    ```
    $ bin/console doctrine:migrations:diff
    $ bin/console doctrine:migrations:migrate
    ```

