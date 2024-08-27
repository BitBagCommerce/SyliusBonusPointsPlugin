
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

1. Import resource in your `config/packages/_sylius.yaml`

    ```yaml
    # config/packages/_sylius.yaml
    
    imports:
    ...
   
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
    use Doctrine\ORM\Mapping as ORM;
    use Sylius\Component\Core\Model\Order as BaseOrder;

    /**
     * @ORM\Entity
     * @ORM\Table(name="sylius_order")
     */
     #[ORM\Entity]
     #[ORM\Table(name: 'sylius_order')]
     class Order extends BaseOrder implements BonusPointsAwareInterface
     {
         #[ORM\Column(type: 'integer', nullable: true)]
         protected ?int $bonusPoints = null;

         public function getBonusPoints(): ?int
         {
            return $this->bonusPoints;
         }

         public function setBonusPoints(?int $bonusPoints): void
         {
            $this->bonusPoints = $bonusPoints;
         }
     }

1. Customize shop templates to include bonus points. For starter You may want copy to templates directory everything from plugin's tests/Application/templates

1. Finish the installation by updating the database schema and installing assets:

    ```
    $ bin/console doctrine:migrations:diff
    $ bin/console doctrine:migrations:migrate
    ```

