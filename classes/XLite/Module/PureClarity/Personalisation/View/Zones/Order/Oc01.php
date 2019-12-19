<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\View\Zones\Order;

use XLite\Module\PureClarity\Personalisation\View\Zones\AZone;

/**
 * Zone Widget - OC-01
 *
 * @ListChild (list="checkout.success", zone="customer", weight="101")
 */
class Oc01 extends AZone
{
    protected $zoneId = 'OC-01';
}
