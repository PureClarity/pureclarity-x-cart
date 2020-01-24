<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalization\View\Menu\Admin;

/**
 * Class LeftMenu
 *
 * Decorator of \XLite\View\Menu\Admin\LeftMenu
 *
 * Adds PureClarity menu option as a "Content" submenu item
 */
abstract class LeftMenu extends \XLite\View\Menu\Admin\LeftMenu implements \XLite\Base\IDecorator
{
    /**
     * Adds PureClarity menu option as a "Content" submenu item
     *
     * @return array
     */
    protected function defineItems()
    {
        $return = parent::defineItems();

        $target = 'pureclarity_dashboard';

        $return['content'][self::ITEM_CHILDREN]['pureclarity'] = [
            self::ITEM_TITLE      => static::t('PureClarity'),
            self::ITEM_TARGET     => $target,
            self::ITEM_CLASS      => 'pureclarity',
            self::ITEM_PERMISSION => 'manage custom pages',
            self::ITEM_WEIGHT     => 500,
        ];

        return $return;
    }
}
