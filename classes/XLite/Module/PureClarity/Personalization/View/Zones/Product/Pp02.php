<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE for license details.
 */

namespace XLite\Module\PureClarity\Personalization\View\Zones\Product;

use XLite\Module\PureClarity\Personalization\View\Zones\AZone;

/**
 * Zone Widget - PP-02
 *
 * @ListChild (list="center.bottom", zone="customer", weight="102")
 */
class Pp02 extends AZone
{
    /** @var string $zoneId */
    protected $zoneId = 'PP-02';

    /**
     * Adds product as an allowed target, so it displays on the product details page
     *
     * @return string[]
     */
    public static function getAllowedTargets() : array
    {
        $result = parent::getAllowedTargets();
        $result[] = 'product';
        return $result;
    }
}
