<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\View\Menu\Admin;

/**
 * Top menu widget
 */
abstract class LeftMenu extends \XLite\View\Menu\Admin\LeftMenu implements \XLite\Base\IDecorator
{
    /**
     * Define and set handler attributes; initialize handler
     *
     * @param array $params Handler params OPTIONAL
     */
    public function __construct(array $params = [])
    {
        parent::__construct($params);

        $this->relatedTargets['pureclarity'][] = 'pureclarity';
    }

    /**
     * Define items
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
