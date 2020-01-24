<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalization\View\Admin\Dashboard;

use XLite\View\AView;

/**
 * Pureclarity Dashboard - Waiting View
 */
class Waiting extends AView
{
    /**
     * Gets the current list of JS for the overall View and adds the js for this view to it
     *
     * @return array
     */
    public function getJSFiles() : array
    {
        $list = parent::getJSFiles();
        $list[] = $this->getDir() . '/js/waiting.js';

        return $list;
    }

    /**
     * Returns the PureClarity module templates directory
     *
     * @return string
     */
    protected function getDir() : string
    {
        return 'modules/PureClarity/Personalization/admin/dashboard';
    }

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
        return 'modules/PureClarity/Personalization/admin/dashboard/waiting.twig';
    }
}
