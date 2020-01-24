<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalization\View\Form\Admin\Dashboard;

use XLite\View\Form\AForm;

/**
 * Class Feeds
 *
 * Form class for Feeds popup form on dashboard page
 */
class Feeds extends AForm
{
    /**
     * Returns target for this form to submit to
     *
     * @return string
     */
    protected function getDefaultTarget() : string
    {
        return 'pureclarity_feeds';
    }

    /**
     * Returns action on the target for this form to post to
     *
     * @return string
     */
    protected function getDefaultAction() : string
    {
        return 'request';
    }

    /**
     * Returns class for form
     *
     * @return string
     */
    protected function getClassName() : string
    {
        return parent::getClassName() . ' pureclarity-feeds';
    }
}
