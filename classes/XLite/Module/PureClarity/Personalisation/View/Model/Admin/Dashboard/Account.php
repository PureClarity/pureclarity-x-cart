<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\View\Model\Admin\Dashboard;

use XLite\Model\AEntity;
use XLite\View\Button\AButton;
use XLite\View\Button\Submit;
use XLite\View\Model\AModel;

class Account extends AModel
{
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
     * @return AEntity|null
     */
    protected function getDefaultModelObject()
    {
        return null;
    }

    /**
     * @return string
     */
    protected function getFormClass()
    {
        return 'XLite\Module\PureClarity\Personalisation\View\Form\Admin\Dashboard\Account';
    }

    /**
     * @return array
     */
    protected function getFormButtons()
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
     * @return string|null
     */
    protected function getButtonPanelClass()
    {
        return null;
    }
}
