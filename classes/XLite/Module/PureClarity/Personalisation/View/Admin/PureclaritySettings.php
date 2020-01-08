<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\View\Admin;

/**
 * Pureclarity Settings View
 */
class PureclaritySettings extends \XLite\View\Dialog
{
    /**
     * Adds pureclarity_settings as an allowed target, so it displays on that target
     *
     * @return array
     */
    public static function getAllowedTargets() : array
    {
        $result = parent::getAllowedTargets();
        $result[] = 'pureclarity_settings';

        return $result;
    }

    /**
     * Returns the PureClarity module templates directory
     *
     * @return string
     */
    protected function getDir() : string
    {
        return 'modules/PureClarity/Personalisation/admin';
    }
}