<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\View\Model\Admin\Dashboard;

use XLite\Module\PureClarity\Personalisation\Core\PureClarity;
use XLite\View\Button\AButton;
use XLite\View\Button\Submit;
use XLite\View\Model\AModel;

/**
 * Class Feeds
 *
 * Form Model for Feeds popup form on configured dashboard page
 */
class Feeds extends AModel
{
    /**
     * Default schema for the Existing Account form
     *
     * @var array
     */
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
     * Removes brand field if brand feed is not enabled
     *
     * @return void
     */
    protected function defineFormFields()
    {
        $pc = PureClarity::getInstance();
        $brandFeedEnabled = $pc->getConfigFlag(PureClarity::CONFIG_FEEDS_BRAND);
        $brandParent = $pc->getConfig(PureClarity::CONFIG_FEEDS_BRAND_PARENT);

        if ($brandFeedEnabled === false || empty($brandParent)) {
            unset($this->schemaDefault['brand']);
        }

        parent::defineFormFields();
    }

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
        return 'XLite\Module\PureClarity\Personalisation\View\Form\Admin\Dashboard\Feeds';
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
                AButton::PARAM_LABEL    => 'Send Feeds',
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
