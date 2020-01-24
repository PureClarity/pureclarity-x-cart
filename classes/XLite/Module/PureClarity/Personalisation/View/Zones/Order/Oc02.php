<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalization\View\Zones\Order;

use XLite\Module\PureClarity\Personalization\View\Zones\AZone;

/**
 * Zone Widget - OC-02
 *
 * @ListChild (list="checkout.success", zone="customer", weight="102")
 */
class Oc02 extends AZone
{
    /** @var string $zoneId */
    protected $zoneId = 'OC-02';

    /**
     * Adds checkoutSuccess as an allowed target, so it displays on the checkout success page
     *
     * @return string[]
     */
    public static function getAllowedTargets() : array
    {
        $result = parent::getAllowedTargets();
        $result[] = 'checkoutSuccess';
        return $result;
    }
}
