# UPGRADE FROM `v1.2.0` TO `v2.0`

New fields were added to BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsInterface:

- `$originalBonusPoints` which has a value of self or null (is not null when the field isUsed is true)
- `$relatedBonusPoints` which has a value of self or null (is a collection of self's, has any element when any used bonus points are related to the parent)"


> New class `BitBag\SyliusBonusPointsPlugin\Creator\BonusPointsCreator` were added which is responsible to create new BonusPoints entity based on provided data's.


> [BC Break] - The new calculation of points breaks compatibility with existing data records in the db

### What has changed?

>The previous logic deductively estimated the number of remaining bonus points available, which resulted in erroneous data when the customer had a lot of points,
used points and expired points, the previous logic did not specifically count how many points were used from a given pool.
The new logic saves creates entries in used but still saves previously `OriginalBonusPoints` (BonusPoints.isUsed=false) as the pool from which points were deducted (in order to accurately calculate all pools). 


### How migrate old data ? (idea)

>As mentioned earlier, the points were calculated by estimation/deduction therefore there is no ideal way to transfer existing points.
But one way could be to try to calculate each record by deduction (as in the previous implementation) the number of available points but for each row
i.e. we need to take into account the fields with bonus points `(expiresAt, updatedAt, isUsed)`. 
We would need to retrieve all transactional records from the `BonusPoints` entity one by one sorting by expiration date 
and then estimate on what date the points were used (`updatedAt`, `isUsed=true`) and estimate how many for `X` record should be used points and 
then remove all `isUsed=true` records and add anew taking into account the changes in the new version `2.0.1`.
