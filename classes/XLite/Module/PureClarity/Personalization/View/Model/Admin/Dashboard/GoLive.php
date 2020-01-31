<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE for license details.
 */

namespace XLite\Module\PureClarity\Personalization\View\Model\Admin\Dashboard;

use XLite\View\Button\AButton;
use XLite\View\Button\Submit;
use XLite\View\Model\AModel;

/**
 * Class GoLive
 *
 * Form Model for "Go Live" button on configured dashboard page
 */
class GoLive extends AModel
{
    /**
     * Default schema for the "Go Live" form
     *
     * @var array
     */
    protected $schemaDefault = [];

    /**
     * No object needed as this is not a normal form (i.e. no data object related to this, as it's not it's own entity)
     */
    protected function getDefaultModelObject() : void
    {
        return;
    }

    /**
     * Gets the main form class for this form
     *
     * @return string
     */
    protected function getFormClass() : string
    {
        return 'XLite\Module\PureClarity\Personalization\View\Form\Admin\Dashboard\GoLive';
    }

    /**
     * Defines the button on this form
     *
     * @return array
     */
    protected function getFormButtons() : array
    {
        $result = parent::getFormButtons();

        $result['submit'] = new Submit(
            [
                AButton::PARAM_LABEL    => 'Go Live',
                AButton::PARAM_BTN_TYPE => 'btn regular-main-button submit',
            ]
        );

        return $result;
    }

    /**
     * No button panel class needed here, as we've got our own styling
     */
    protected function getButtonPanelClass() : void
    {
        return;
    }
}
