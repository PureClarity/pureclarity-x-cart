<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\View\Zones\Basket;

use XLite\Module\PureClarity\Personalisation\View\Zones\AZone;

/**
 * Zone Widget - BP-02
 *
 * @ListChild (list="center.bottom", zone="customer", weight="102")
 */
class Bp02 extends AZone
{
    protected $zoneId = 'BP-02';

    /**
     * Return list of allowed targets
     *
     * @return string[]
     */
    public static function getAllowedTargets()
    {
        $result = parent::getAllowedTargets();
        $result[] = 'cart';
        return $result;
    }
}
