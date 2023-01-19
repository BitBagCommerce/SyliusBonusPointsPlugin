# UPGRADE FROM `v1.2.0` TO `v1.2.1`

New fields were added to BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsInterface:

- `$originalBonusPoints` which has a value of self or null (is not null when the field isUsed is true)
- `$relatedBonusPoints` which has a value of self or null (is a collection of self's, has any element when any used bonus points are related to the parent)"

New class `BitBag\SyliusBonusPointsPlugin\Creator\BonusPointsCreator` were added which is responsible to create new BonusPoints entity based on provided data's.

-----
what has changed?
----
The previous logic deductively estimated the number of remaining bonus points available, which resulted in erroneous data when the customer had a lot of points,
used points and expired points, the previous logic did not specifically count how many points were used from a given pool.
The new logic saves creates entries in used but still saves previously `OriginalBonusPoints` (BonusPoints.isUsed=false) as the pool from which points were deducted (in order to accurately calculate all pools). 
----