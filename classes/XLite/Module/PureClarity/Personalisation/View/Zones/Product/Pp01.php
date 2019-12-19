<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\View\Zones\Product;

use XLite\Module\PureClarity\Personalisation\View\Zones\AZone;

/**
 * Zone Widget - PP-01
 *
 * @ListChild (list="product.details.page", zone="customer", weight="200")
 */
class Pp01 extends AZone
{
    protected $zoneId = 'PP-01';

    /**
     * Return list of allowed targets
     *
     * @return string[]
     */
    public static function getAllowedTargets()
    {
        $result = parent::getAllowedTargets();
        $result[] = 'product';
        return $result;
    }
}
