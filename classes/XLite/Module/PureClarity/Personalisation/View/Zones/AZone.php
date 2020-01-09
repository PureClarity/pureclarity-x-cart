<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\View\Zones;

use XLite\Module\PureClarity\Personalisation\Core\PureClarity;
use XLite\View\AView;

/**
 * Zone abstract class
 *
 * Default view class for all PureClarity Zones
 */
abstract class AZone extends AView
{
    /** @var string $zoneId */
    protected $zoneId = '';

    /**
     * Returns any DATA for this Zone for the "data-pureclarity" attribute
     * @return string
     */
    public function getBmzData() : string
    {
        return 'bmz:' . $this->zoneId;
    }

    /**
     * Returns the classes for this Zone
     *
     * @return string
     */
    public function getClasses() : string
    {
        return $this->showDebug() ? 'pc-debug' : '';
    }

    /**
     * Returns the ID for this Zone
     *
     * @return string
     */
    public function getZoneId() : string
    {
        return $this->zoneId;
    }

    /**
     * Returns whether zone debug is enabled
     *
     * @return bool
     */
    public function showDebug() : bool
    {
        return PureClarity::getInstance()->isZoneDebugEnabled();
    }

    /**
     * Return widget default template
     *
     * @return string
     */
    protected function getDefaultTemplate() : string
    {
        return 'modules/PureClarity/Personalisation/zone.twig';
    }

    /**
     * Returns whether this Widget should be visible or not
     *
     * @return bool
     */
    protected function isVisible() : bool
    {
        $pc = PureClarity::getInstance();
        return $pc->isActive() && $pc->isZoneActive($this->zoneId);
    }

    /**
     * Get a list of JavaScript files required to display the widget properly
     *
     * @return array
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();
        $list[] = 'items_list/items_list.js';
        $list[] = 'items_list/product/products_list.js';
        return $list;
    }
}
