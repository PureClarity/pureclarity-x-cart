<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\View\Tabs;

/**
 * Tabs related to payment settings
 *
 * @ListChild (list="admin.center", zone="admin")
 */
class SettingsTabs extends \XLite\View\Tabs\ATabs
{
    /**
     * Returns the list of targets where this widget is available
     *
     * @return string
     */
    public static function getAllowedTargets()
    {
        $list = parent::getAllowedTargets();
        $list[] = 'pureclarity_settings';

        return $list;
    }

    /**
     * @return array
     */
    protected function defineTabs()
    {
        return [
            'pureclarity_settings'   => [
                'weight'   => 100,
                'title'    => static::t('Settings'),
                'widget' => 'XLite\Module\PureClarity\Personalisation\View\Admin\PureclaritySettings',
            ]
        ];
    }
}
