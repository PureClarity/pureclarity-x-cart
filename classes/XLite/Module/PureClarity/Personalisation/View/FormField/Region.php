<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\View\FormField;

use PureClarity\Api\Resource\Regions;
use XLite\View\FormField\Select\Regular;

/**
 * PureClarity Region Selector
 */
class Region extends Regular
{
    /**
     * Gets Regions from PureClarity PHP SDK as options for the select field
     *
     * @return array
     */
    protected function getDefaultOptions() : array
    {
        $regionClass = new Regions();
        $pcRegions = $regionClass->getRegionLabels();

        $regions = [];

        foreach ($pcRegions as $id => $region) {
            $regions[$region['value']] = $region['label'];
        }

        return $regions;
    }
}
