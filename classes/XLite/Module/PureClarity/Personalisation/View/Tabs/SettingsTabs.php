<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\View\Tabs;

/**
 * Tabs related to PureClarity
 *
 * @ListChild (list="admin.center", zone="admin")
 */
class SettingsTabs extends \XLite\View\Tabs\ATabs
{
    /**
     * Adds pureclarity_settings as an allowed target, so it displays on that target
     *
     * @return array
     */
    public static function getAllowedTargets() : array
    {
        $list = parent::getAllowedTargets();
        $list[] = 'pureclarity_settings';

        return $list;
    }

    /**
     * Adds Settings Tab
     *
     * @return array
     */
    protected function defineTabs() : array
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
