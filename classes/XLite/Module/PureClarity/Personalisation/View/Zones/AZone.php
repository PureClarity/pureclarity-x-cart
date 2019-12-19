<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\View\Zones;

use XLite\Module\PureClarity\Personalisation\Core\Pureclarity;

/**
 * Common widget extention.
 * This widget is used only to link additional css and js files to the page
 */
abstract class AZone extends \XLite\View\AView
{
    protected $zoneId = '';

    /**
     * @return string
     */
    public function getBmzData()
    {
        return 'bmz:' . $this->zoneId;
    }

    /**
     * @return string
     */
    public function getClasses()
    {
        return $this->showDebug() ? 'pc-debug' : '';
    }

    /**
     * @return string
     */
    public function getZoneId()
    {
        return $this->zoneId;
    }

    /**
     * @return string
     */
    public function showDebug()
    {
        return Pureclarity::getInstance()->isZoneDebugEnabled();
    }

    /**
     * Return widget default template
     *
     * @return string
     */
    protected function getDefaultTemplate()
    {
        return 'modules/PureClarity/Personalisation/zone.twig';
    }

    /**
     * Return widget default template
     *
     * @return string
     */
    protected function isVisible()
    {

        $pc = Pureclarity::getInstance();
        return $pc->isActive() && $pc->isZoneActive($this->zoneId);
    }
}
