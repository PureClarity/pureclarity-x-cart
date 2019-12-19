<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\View\FormField;

use PureClarity\Api\Resource\Regions;
use XLite\View\FormField\Select\Regular;

/**
 * Shipping table type
 */
class Region extends Regular
{
    /**
     * getDefaultOptions
     *
     * @return array
     */
    protected function getDefaultOptions()
    {
        $pcRegions = Regions::getRegionLabels();

        $regions = [];

        foreach ($pcRegions as $id => $region) {
            $regions[$region['value']] = $region['label'];
        }

        return $regions;
    }
}
