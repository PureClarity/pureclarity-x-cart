<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE for license details.
 */

namespace XLite\Module\PureClarity\Personalization\View\Zones\Product;

use XLite\Module\PureClarity\Personalization\View\Zones\AZone;

/**
 * Zone Widget - PP-01
 *
 * @ListChild (list="product.details.page", zone="customer", weight="200")
 */
class Pp01 extends AZone
{
    /** @var string $zoneId */
    protected $zoneId = 'PP-01';

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
