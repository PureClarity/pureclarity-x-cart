<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\View\Customer;

use PureClarity\Api\Resource\Endpoints;
use XLite\Module\PureClarity\Personalisation\Core\PureClarity;
use XLite\View\AView;

/**
 * PureClarity Master event class
 * This widget embeds Master PureClarity JS snippet on every page
 *
 * @ListChild (list="head", zone="customer", weight="101")
 */
class Master extends AView
{
    /**
     * Return templates directory name
     *
     * @return string
     */
    protected function getDir() : string
    {
        return 'modules/PureClarity/Personalisation/';
    }
    /**
     * Return widget template
     *
     * @return string
     */
    protected function getDefaultTemplate() : string
    {
        return 'modules/PureClarity/Personalisation/events/master.twig';
    }

    /**
     * Returns whether this Widget should be visible or not
     *
     * @return bool
     */
    protected function isVisible() : bool
    {
        return PureClarity::getInstance()->isActive();
    }

    /**
     * Returns the URL to use to get the PureClarity Applications ClientScript
     *
     * @return string
     */
    public function getClientScriptUrl() : string
    {
        return Endpoints::getClientScriptUrl(PureClarity::getInstance()->getConfig('access_key'));
    }
}
