<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\View\Tabs;

/**
 * Tabs related to PureClarity
 */
class SettingsTabsVariants extends SettingsTabs implements \XLite\Base\IDecorator
{
    /**
     * Adds pureclarity_dashboard as an allowed target, so it displays on that target
     *
     * @return array
     */
    public static function getAllowedTargets() : array
    {
        $list = parent::getAllowedTargets();
        $list[] = 'pureclarity_dashboard';

        return $list;
    }

    /**
     * Adds Dashboard Tab
     *
     * @return array
     */
    protected function defineTabs() : array
    {
        $tabs = parent::defineTabs();
        $tabs['pureclarity_dashboard'] = [
            'weight'   => 50,
            'title'    => static::t('Dashboard'),
            'widget' => 'XLite\Module\PureClarity\Personalisation\View\Admin\PureclarityDashboard',
        ];

        return $tabs;
    }
}
