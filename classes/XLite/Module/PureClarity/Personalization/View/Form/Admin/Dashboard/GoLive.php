<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE for license details.
 */

namespace XLite\Module\PureClarity\Personalization\View\Form\Admin\Dashboard;

use XLite\View\Form\AForm;

/**
 * Class GoLive
 *
 * Form class for "Go Live" form on configured dashboard page
 */
class GoLive extends AForm
{
    /**
     * Adds "Go Live" button JS to the Dashboard page
     *
     * @return array
     */
    public function getJSFiles() : array
    {
        $list = parent::getJSFiles();
        $list[] = 'modules/PureClarity/Personalization/admin/dashboard/js/go-live.js';

        return $list;
    }

    /**
     * Returns target for this form to submit to
     *
     * @return string
     */
    protected function getDefaultTarget() : string
    {
        return 'pureclarity_dashboard';
    }

    /**
     * Returns action on the target for this form to post to
     *
     * @return string
     */
    protected function getDefaultAction() : string
    {
        return 'go_live';
    }
}
