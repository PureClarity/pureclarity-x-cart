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

class Feeds extends AModel
{
    protected $schemaDefault = [
        'product' => [
            self::SCHEMA_CLASS => 'XLite\View\FormField\Input\Checkbox',
            self::SCHEMA_LABEL => 'Product'
        ],
        'category' => [
            self::SCHEMA_CLASS => 'XLite\View\FormField\Input\Checkbox',
            self::SCHEMA_LABEL => 'Category'
        ],
        'user' => [
            self::SCHEMA_CLASS => 'XLite\View\FormField\Input\Checkbox',
            self::SCHEMA_LABEL => 'User'
        ],
        'brand' => [
            self::SCHEMA_CLASS => 'XLite\View\FormField\Input\Checkbox',
            self::SCHEMA_LABEL => 'Brand'
        ],
        'order' => [
            self::SCHEMA_CLASS => 'XLite\View\FormField\Input\Checkbox',
            self::SCHEMA_LABEL => 'Order'
        ],
        'label1' => [
            self::SCHEMA_CLASS => 'XLite\View\FormField\Label',
            self::SCHEMA_LABEL => 'Note: Order history should only need to be sent on '
                                . 'setup as real-time orders are sent to PureClarity'
        ],
        'label2' => [
            self::SCHEMA_CLASS => 'XLite\View\FormField\Label',
            self::SCHEMA_LABEL => 'The chosen feeds will sent to PureClarity when the scheduled task runs, '
                                . 'it can take up to one minute to start.'
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
        return 'XLite\Module\PureClarity\Personalisation\View\Form\Admin\Dashboard\Feeds';
    }

    /**
     * @return array
     */
    protected function getFormButtons()
    {
        $result = parent::getFormButtons();

        $result['submit'] = new Submit(
            [
                AButton::PARAM_LABEL    => 'Send Feeds',
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
