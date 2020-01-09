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
    /** @var string $zoneId */
    protected $zoneId = 'HP-01';

    /**
     * Adds main as an allowed target, so it displays on the home page
     *
     * @return string[]
     */
    public static function getAllowedTargets() : array
    {
        $result = parent::getAllowedTargets();
        $result[] = 'main';
        return $result;
    }
}
