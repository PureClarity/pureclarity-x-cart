<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE for license details.
 */

namespace XLite\Module\PureClarity\Personalization\View\Form\Admin\Dashboard;

use XLite\View\Form\AForm;

/**
 * Class Account
 *
 * Form class for Existing Account form on default dashboard page
 */
class Account extends AForm
{
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
        return 'link_account';
    }
}
