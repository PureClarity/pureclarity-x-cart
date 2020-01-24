<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalization\View\Admin\Dashboard;

use XLite\View\SimpleDialog;

/**
 * Pureclarity Dashboard - Signup Popup View
 */
class Signup extends SimpleDialog
{
    /**
     * Adds pureclarity_signup as an allowed target, so it displays on that target
     *
     * @return array
     */
    public static function getAllowedTargets() : array
    {
        $list = parent::getAllowedTargets();
        $list[] = 'pureclarity_signup';

        return $list;
    }

    /**
     * Returns the .twig template file for this view
     *
     * @return string
     */
    protected function getBody() : string
    {
        return 'modules/PureClarity/Personalization/admin/dashboard/signup.twig';
    }
}
