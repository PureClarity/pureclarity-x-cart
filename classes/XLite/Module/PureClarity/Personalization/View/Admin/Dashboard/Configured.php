<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE for license details.
 */

namespace XLite\Module\PureClarity\Personalization\View\Admin\Dashboard;

use XLite\View\AView;

/**
 * Pureclarity Dashboard - Configured Page View
 */
class Configured extends AView
{
    /**
     * Adds pureclarity_dashboard as an allowed target, so it displays on that target
     *
     * @return array
     */
    public static function getAllowedTargets() : array
    {
        $result = parent::getAllowedTargets();
        $result[] = 'pureclarity_dashboard';

        return $result;
    }

    /**
     * Returns the .twig template file for this view
     *
     * @return string
     */
    protected function getDefaultTemplate() : string
    {
        return 'modules/PureClarity/Personalization/admin/dashboard/configured.twig';
    }
}
