<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalization\View\Model\Admin\Dashboard;

use DateTime;
use Exception;
use XLite;
use XLite\Core\ConfigParser;
use XLite\Core\Converter;
use XLite\View\Button\AButton;
use XLite\View\Button\Submit;
use XLite\View\Model\AModel;
use XLite\Core\Session;

/**
 * Class Signup
 *
 * Form Model for Signup popup form on default dashboard page
 */
class Signup extends AModel
{
    /**
     * Schema for "About you" section of the form
     *
     * @var array
     */
    protected $schemaYou = [
        'sep_about_you_header' => [
            self::SCHEMA_CLASS     => 'XLite\Module\PureClarity\Personalization\View\FormField\SubHeader',
            self::SCHEMA_LABEL     => 'About you',
        ],
        'first_name' => [
            self::SCHEMA_CLASS     => 'XLite\View\FormField\Input\Text',
            self::SCHEMA_LABEL     => 'First Name',
            self::SCHEMA_REQUIRED  => true,
        ],
        'last_name' => [
            self::SCHEMA_CLASS     => 'XLite\View\FormField\Input\Text',
            self::SCHEMA_LABEL     => 'Last Name',
            self::SCHEMA_REQUIRED  => true,
        ],
        'email' => [
            self::SCHEMA_CLASS     => 'XLite\View\FormField\Input\Text\Email',
            self::SCHEMA_LABEL     => 'Email',
            self::SCHEMA_REQUIRED  => true,
        ],
        'company' => [
            self::SCHEMA_CLASS     => 'XLite\View\FormField\Input\Text',
            self::SCHEMA_LABEL     => 'Company',
            self::SCHEMA_REQUIRED  => true,
        ],
        'password' => [
            self::SCHEMA_CLASS     => 'XLite\Module\PureClarity\Personalization\View\FormField\Password',
            self::SCHEMA_LABEL     => 'Password',
            self::SCHEMA_REQUIRED  => true,
        ],
    ];

    /**
     * Schema for "About the store" section of the form
     *
     * @var array
     */
    protected $schemaStore = [
        'sep_about_store_header' => [
            self::SCHEMA_CLASS     => 'XLite\Module\PureClarity\Personalization\View\FormField\SubHeader',
            self::SCHEMA_LABEL     => 'About the Store',
        ],
        'store_name' => [
            self::SCHEMA_CLASS     => 'XLite\View\FormField\Input\Text',
            self::SCHEMA_LABEL     => 'Store Name',
            self::SCHEMA_REQUIRED  => true,
        ],
        'url' => [
            self::SCHEMA_CLASS     => 'XLite\View\FormField\Input\Text\URL',
            self::SCHEMA_LABEL     => 'URL',
            self::SCHEMA_REQUIRED  => true,
        ],
        'currency' => [
            self::SCHEMA_CLASS     => 'XLite\View\FormField\Label',
            self::SCHEMA_LABEL     => 'Currency'
        ],
        'timezone' => [
            self::SCHEMA_CLASS     => 'XLite\View\FormField\Label',
            self::SCHEMA_LABEL     => 'Timezone'
        ],
        'region' => [
            self::SCHEMA_CLASS      => 'XLite\Module\PureClarity\Personalization\View\FormField\Region',
            self::SCHEMA_LABEL      => 'Region',
            self::SCHEMA_REQUIRED   => true,
        ]
    ];

    /**
     * Adds the custom sections to the form
     *
     * @param array $params   Widget params OPTIONAL
     * @param array $sections Sections list OPTIONAL
     */
    public function __construct(array $params = [], array $sections = [])
    {
        $this->sections['you'] = 'About you';
        $this->sections['store'] = 'About the Store';

        parent::__construct($params, $sections);
    }

    /**
     * No object needed as this is not a normal form (i.e. no data object related to this, as it's not it's own entity)
     */
    protected function getDefaultModelObject() : void
    {
        return;
    }

    /**
     * Returns the default value for the provided field name
     *
     * Will either return a calculated default value (on first load) or from session (if submitted with errors)
     *
     * @param string $name
     * @return mixed|string
     * @throws Exception
     */
    public function getDefaultFieldValue($name)
    {
        $value = parent::getDefaultFieldValue($name);

        $lastSubmit = Session::getInstance()->pcSignupParams;

        if (!empty($lastSubmit)) {
            switch ($name) {
                case 'first_name':
                    $value = $lastSubmit['firstname'];
                    break;
                case 'last_name':
                    $value = $lastSubmit['lastname'];
                    break;
                case 'email':
                    $value = $lastSubmit['email'];
                    break;
                case 'company':
                    $value = $lastSubmit['company'];
                    break;
                case 'password':
                    $value = $lastSubmit['password'];
                    break;
                case 'url':
                    $value = $lastSubmit['url'];
                    break;
                case 'store_name':
                    $value = $lastSubmit['store_name'];
                    break;
                case 'region':
                    $value = $lastSubmit['region'];
                    break;
                case 'currency':
                    $value = $lastSubmit['currency'];
                    break;
                case 'timezone':
                    $value = $lastSubmit['timezone'];
                    break;
                default:
            }
        } else {
            switch ($name) {
                case 'currency':
                    $value = XLite::getInstance()->getCurrency()->getCode();
                    break;
                case 'timezone':
                    $time = new DateTime('now', Converter::getTimeZone());
                    $value = $time->getTimezone()->getName();
                    if ($value == 'UTC') {
                        $value = 'Europe/London';
                    }
                    break;
                case 'url':
                    $domain = ConfigParser::getOptions(['host_details', 'http_host']);
                    $value = 'http://' . $domain;
                    break;
                default:
            }
        }

        return $value;
    }

    /**
     * Gets the main form class for this form
     *
     * @return string
     */
    protected function getFormClass() : string
    {
        return 'XLite\Module\PureClarity\Personalization\View\Form\Admin\Dashboard\Signup';
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
                AButton::PARAM_LABEL    => 'Sign up',
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
