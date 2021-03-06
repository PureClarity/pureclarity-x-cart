<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE for license details.
 */

namespace XLite\Module\PureClarity\Personalization\View\Zones\Homepage;

use XLite\Module\PureClarity\Personalization\View\Zones\AZone;

/**
 * Zone Widget - HP-03
 *
 * @ListChild (list="center.bottom", zone="customer", weight="103")
 */
class Hp03 extends AZone
{
    /** @var string $zoneId */
    protected $zoneId = 'HP-03';

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
