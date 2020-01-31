<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE for license details.
 */

namespace XLite\Module\PureClarity\Personalization\View\Zones\Basket;

use XLite\Module\PureClarity\Personalization\View\Zones\AZone;

/**
 * Zone Widget - BP-01
 *
 * @ListChild (list="center.bottom", zone="customer", weight="101")
 */
class Bp01 extends AZone
{
    /** @var string $zoneId */
    protected $zoneId = 'BP-01';

    /**
     * Adds cart as an allowed target, so it displays on the cart page
     *
     * @return string[]
     */
    public static function getAllowedTargets() : array
    {
        $result = parent::getAllowedTargets();
        $result[] = 'cart';
        return $result;
    }
}
