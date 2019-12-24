<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\View\Admin;

use XLite\View\AView;

/**
 * Pureclarity Dashboard View
 */
class PureclarityDashboard extends AView
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
     * Returns the PureClarity module templates directory
     *
     * @return string
     */
    protected function getDir() : string
    {
        return 'modules/PureClarity/Personalisation/admin/dashboard';
    }

    /**
     * Gets the current list of CSS for the overall View and adds the js for this view to it
     *
     * @return array
     */
    public function getCSSFiles() : array
    {
        $list = parent::getCSSFiles();
        $list[] = $this->getDir() . '/css/dashboard.css';

        return $list;
    }

    /**
     * Returns the .twig template file for this view
     *
     * @return string
     */
    protected function getDefaultTemplate() : string
    {
        return 'modules/PureClarity/Personalisation/admin/dashboard.twig';
    }
}
