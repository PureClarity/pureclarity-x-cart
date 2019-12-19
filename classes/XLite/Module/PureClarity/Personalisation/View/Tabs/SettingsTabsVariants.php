<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\View\Tabs;

/**
 * Tabs related to payment settings
 */
class SettingsTabsVariants extends \XLite\Module\PureClarity\Personalisation\View\Tabs\SettingsTabs implements \XLite\Base\IDecorator
{
    /**
     * Returns the list of targets where this widget is available
     *
     * @return string
     */
    public static function getAllowedTargets()
    {
        $list = parent::getAllowedTargets();
        $list[] = 'pureclarity_dashboard';

        return $list;
    }

    /**
     * @return array
     */
    protected function defineTabs()
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
