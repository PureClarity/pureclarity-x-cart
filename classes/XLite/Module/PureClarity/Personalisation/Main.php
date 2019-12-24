<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation;

/**
 * Class Main
 * @package XLite\Module\PureClarity\Personalisation
 *
 * PureClarity Main module class
 */
abstract class Main extends \XLite\Module\AModule
{
    /**
     * Initialises the module, to include the PureClarity sdk autoloader
     */
    public static function init()
    {
        parent::init();
        require_once LC_DIR_MODULES . 'PureClarity' . LC_DS
                                    . 'Personalisation' . LC_DS
                                    . 'lib' . LC_DS . 'php-sdk' . LC_DS
                                    . 'src' . LC_DS . 'autoload.php';
    }

    /**
     * Author name
     *
     * @return string
     */
    public static function getAuthorName()
    {
        return 'PureClarity';
    }

    /**
     * Module name
     *
     * @return string
     */
    public static function getModuleName()
    {
        return 'PureClarity Personalisation';
    }

    /**
     * Module description
     *
     * @return string
     */
    public static function getDescription()
    {
        return 'Integration with PureClarity AI-based eCommerce personalisation platform';
    }
}
