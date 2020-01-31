<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE for license details.
 */

namespace XLite\Module\PureClarity\Personalization\View\Customer;

use PureClarity\Api\Resource\Endpoints;
use XLite\Module\PureClarity\Personalization\Core\PureClarity;
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
        return 'modules/PureClarity/Personalization/';
    }
    /**
     * Return widget template
     *
     * @return string
     */
    protected function getDefaultTemplate() : string
    {
        return 'modules/PureClarity/Personalization/events/master.twig';
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
        $endpoints = new Endpoints();
        return $endpoints->getClientScriptUrl(PureClarity::getInstance()->getConfig('access_key'));
    }
}
