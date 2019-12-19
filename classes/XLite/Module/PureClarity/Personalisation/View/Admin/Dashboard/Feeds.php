<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\View\Admin\Dashboard;

use XLite\View\SimpleDialog;

/**
 * Add shipping method dialog widget
 *
 * @ListChild (list="admin.center", zone="admin")
 */
class Feeds extends SimpleDialog
{
    /**
     * Return list of allowed targets
     *
     * @return array
     */
    public static function getAllowedTargets()
    {
        $list = parent::getAllowedTargets();
        $list[] = 'pureclarity_feeds';

        return $list;
    }

    /**
     * Return file name for the center part template
     *
     * @return string
     */
    protected function getBody()
    {
        return 'modules/PureClarity/Personalisation/admin/dashboard/feeds.twig';
    }
}
