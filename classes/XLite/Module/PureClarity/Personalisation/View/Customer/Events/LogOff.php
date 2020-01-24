<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalization\View\Customer\Events;

use XLite\Core\Session;
use XLite\Module\PureClarity\Personalization\Core\PureClarity;
use XLite\View\AView;

/**
 * PureClarity Currency event class
 * Puts the LogOff event code on all pages, but is only shown immediately after a logoff
 *
 * @ListChild (list="head", zone="customer", weight="104")
 */
class LogOff extends AView
{
    /**
     * Return widget template
     *
     * @return string
     */
    protected function getDefaultTemplate() : string
    {
        return 'modules/PureClarity/Personalization/events/logoff.twig';
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
     * Returns whether the customer_logout event should be run on this page
     *
     * @return bool
     */
    protected function doLogoffEvent() : bool
    {
        $session = Session::getInstance();
        $logoff = $session->pc_logoff;
        $session->pc_logoff = false;
        return $logoff ? true : false;
    }
}
