<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE for license details.
 */

namespace XLite\Module\PureClarity\Personalization\View\Customer\Events;

use XLite;
use XLite\Module\PureClarity\Personalization\Core\PureClarity;
use XLite\View\AView;

/**
 * PureClarity Currency event class
 * Puts the Currency event on all pages
 *
 * @ListChild (list="head", zone="customer", weight="102")
 */
class Currency extends AView
{
    /**
     * Return widget template
     *
     * @return string
     */
    protected function getDefaultTemplate() : string
    {
        return 'modules/PureClarity/Personalization/events/currency.twig';
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
    public function getCurrency() : string
    {
        return XLite::getInstance()->getCurrency()->getCode();
    }
}
