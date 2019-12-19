<?php
namespace XLite\Module\PureClarity\Personalisation;

abstract class Main extends \XLite\Module\AModule
{
    public static function init()
    {
        parent::init();
        include_once LC_DIR_MODULES . 'PureClarity' . LC_DS
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
     * Get module major version
     *
     * @return string
     */
    public static function getMajorVersion()
    {
        return '1.0';
    }

    /**
     * Module version
     *
     * @return string
     */
    public static function getMinorVersion()
    {
        return 0;
    }

    /**
     * Module description
     *
     * @return string
     */
    public static function getDescription()
    {
        return 'Some description here';
    }
}
