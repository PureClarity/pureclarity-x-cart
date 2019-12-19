<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\View\Zones\Homepage;

use XLite\Module\PureClarity\Personalisation\View\Zones\AZone;

/**
 * Zone Widget - HP-01
 *
 * @ListChild (list="center.bottom", zone="customer", weight="101")
 */
class Hp01 extends AZone
{
    protected $zoneId = 'HP-01';

    /**
     * Return list of allowed targets
     *
     * @return string[]
     */
    public static function getAllowedTargets()
    {
        $result = parent::getAllowedTargets();
        $result[] = 'main';
        return $result;
    }
}
