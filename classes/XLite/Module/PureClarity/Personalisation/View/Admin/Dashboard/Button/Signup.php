<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\View\Admin\Dashboard\Button;

/**
 * PureClarity Dashboard - Signup popup button
 */
class Signup extends \XLite\View\Button\APopupButton
{
    /**
     * Adds Feeds popup button JS to the Dashboard page
     *
     * @return array
     */
    public function getJSFiles() : array
    {
        $list = parent::getJSFiles();
        $list[] = 'modules/PureClarity/Personalisation/admin/dashboard/js/signup.js';

        return $list;
    }

    /**
     * Parameters to use in AJAX for popup HTML
     *
     * @return array
     */
    protected function prepareURLParams() : array
    {
        return array(
            'target' => 'pureclarity_signup',
            'widget' => '\XLite\Module\PureClarity\Personalisation\View\Admin\Dashboard\Signup'
        );
    }

    /**
     * Return CSS classes
     *
     * @return string
     */
    protected function getClass() : string
    {
        return parent::getClass() . ' popup-pureclarity-signup';
    }

    /**
     * Returns the default label for the button
     *
     * @return string
     */
    protected function getDefaultLabel() : string
    {
        return 'Sign up';
    }
}
