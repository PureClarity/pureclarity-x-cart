<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalization\View\Admin\Dashboard;

use XLite\View\SimpleDialog;

/**
 * Pureclarity Dashboard - Feeds Popup View
 */
class Feeds extends SimpleDialog
{
    /**
     * Adds pureclarity_feeds as an allowed target, so it displays on that target
     *
     * @return array
     */
    public static function getAllowedTargets() : array
    {
        $list = parent::getAllowedTargets();
        $list[] = 'pureclarity_feeds';

        return $list;
    }

    /**
     * Returns the .twig template file for this view
     *
     * @return string
     */
    protected function getBody() : string
    {
        return 'modules/PureClarity/Personalization/admin/dashboard/feeds.twig';
    }
}
