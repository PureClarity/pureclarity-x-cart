<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\View\Form\Admin\Dashboard;

/**
 * Class Signup
 *
 * Form class for Signup popup form on dashboard page
 */
class Signup extends \XLite\View\Form\AForm
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
        return 'signup';
    }

    /**
     * Returns class for form
     *
     * @return string
     */
    protected function getClassName() : string
    {
        return parent::getClassName() . ' pureclarity-signup';
    }
}
