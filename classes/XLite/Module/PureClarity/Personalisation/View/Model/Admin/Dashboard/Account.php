<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\View\Model\Admin\Dashboard;

use XLite\View\Button\AButton;
use XLite\View\Button\Submit;
use XLite\View\Model\AModel;

/**
 * Class Account
 *
 * Form Model for Existing Account form on default dashboard page
 */
class Account extends AModel
{
    /**
     * Default schema for the Existing Account form
     *
     * @var array
     */
    protected $schemaDefault = [
        'access_key' => [
            self::SCHEMA_CLASS      => 'XLite\View\FormField\Input\Text',
            self::SCHEMA_LABEL      => 'Access Key',
            self::SCHEMA_REQUIRED   => true,
            self::SCHEMA_ATTRIBUTES => [
                'autocomplete'      => 'new-password',
            ],
        ],
        'secret_key' => [
            self::SCHEMA_CLASS      => 'XLite\View\FormField\Input\Password',
            self::SCHEMA_LABEL      => 'Secret Key',
            self::SCHEMA_REQUIRED   => true,
            self::SCHEMA_ATTRIBUTES => [
                'autocomplete'      => 'new-password',
            ],
        ],
        'region' => [
            self::SCHEMA_CLASS      => 'XLite\Module\PureClarity\Personalisation\View\FormField\Region',
            self::SCHEMA_LABEL      => 'Region',
            self::SCHEMA_REQUIRED   => true,
        ],
    ];

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
        return 'XLite\Module\PureClarity\Personalisation\View\Form\Admin\Dashboard\Account';
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
                AButton::PARAM_LABEL    => 'Save Details',
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
