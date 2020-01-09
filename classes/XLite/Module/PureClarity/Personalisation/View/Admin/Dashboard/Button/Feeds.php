<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\View\Admin\Dashboard\Button;

use XLite\View\Button\APopupButton;

/**
 * PureClarity Dashboard - Feed submission popup button
 */
class Feeds extends APopupButton
{
    /**
     * Adds Feeds popup button JS to the Dashboard page
     *
     * @return array
     */
    public function getJSFiles() : array
    {
        $list = parent::getJSFiles();
        $list[] = 'modules/PureClarity/Personalisation/admin/dashboard/js/feeds.js';

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
            'target' => 'pureclarity_feeds',
            'widget' => '\XLite\Module\PureClarity\Personalisation\View\Admin\Dashboard\Feeds'
        );
    }

    /**
     * Return CSS classes
     *
     * @return string
     */
    protected function getClass() : string
    {
        return parent::getClass() . ' popup-pureclarity-feeds';
    }

    /**
     * Returns the default label for the button
     *
     * @return string
     */
    protected function getDefaultLabel() : string
    {
        return 'Run Feeds Manually';
    }
}
