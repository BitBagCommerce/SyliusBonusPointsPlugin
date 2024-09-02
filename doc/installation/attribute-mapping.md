# Attribute-mapping

Check the mapping settings in `config/packages/doctrine.yaml` and, if necessary, change them accordingly.
```yaml
doctrine:
    ...
    orm:
        ...
        mappings:
            App:
                ...
                type: attribute
```

Extend entities with parameters and methods using attributes and traits:
```php
<?php
// src/Entity/Order/Order.php

declare(strict_types=1);

namespace App\Entity\Order;

use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsAwareInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsAwareTrait;
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
     use BonusPointsAwareTrait;

     #[ORM\Column(type: 'integer', nullable: true)]
     protected $bonusPoints = null;
 }
```
