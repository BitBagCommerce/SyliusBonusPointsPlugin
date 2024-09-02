# XML-mapping

Check the mapping settings in `config/packages/doctrine.yaml` and, if necessary, change them accordingly.
```yaml
doctrine:
    ...
    orm:
        ...
        mappings:
            App:
                ...
                type: xml
                dir: '%kernel.project_dir%/src/Resources/config/doctrine'
```

Extend entities with parameters and methods using attributes and traits:

```php
<?php
// src/Entity/Order.php

declare(strict_types=1);

namespace App\Entity;

use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsAwareInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsAwareTrait;
use Sylius\Component\Core\Model\Order as BaseOrder;

 class Order extends BaseOrder implements BonusPointsAwareInterface
 {
    use BonusPointsAwareTrait;
 }
```

Define new Entity mapping inside `src/Resources/config/doctrine` directory.
```xml
<?xml version="1.0" encoding="UTF-8"?>

<doctrine-mapping xmlns="http://doctrine-project.org/schemas/orm/doctrine-mapping"
                  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                  xsi:schemaLocation="http://doctrine-project.org/schemas/orm/doctrine-mapping
                            http://doctrine-project.org/schemas/orm/doctrine-mapping.xsd"
>
    <entity name="App\Entity\Order" table="sylius_order">
    </entity>
</doctrine-mapping>
```

Override `config/packages/_sylius.yaml` configuration:
```yaml
# config/_sylius.yaml

sylius_order:
    resources:
        order:
            classes:
                model: App\Entity\Order

```
