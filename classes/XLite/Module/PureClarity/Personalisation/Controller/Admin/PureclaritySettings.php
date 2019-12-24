<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\Controller\Admin;

use XLite\Controller\Admin\AAdmin;
use XLite\Core\Database;

/**
 * Class PureclaritySettings
 *
 * PureClarity Settings Page Controller
 */
class PureclaritySettings extends AAdmin
{
    /**
     * Returns page title
     *
     * @return string
     */
    public function getTitle() : string
    {
        return static::t('PureClarity Settings');
    }

    /**
     * Updates the PureClarity module settings
     *
     * @return void
     */
    protected function doActionUpdate() : void
    {
        $this->getModelForm()->performAction('update');
    }

    /**
     * Returns default Settings model, no PureClarity-specific one needed
     *
     * @return string
     */
    protected function getModelFormClass() : string
    {
        return 'XLite\View\Model\Settings';
    }

    /**
     * Returns all PureClarity settings (see install.yaml)
     *
     * @return array
     */
    public function getOptions() : array
    {
        return $this->executeCachedRuntime(function () {
            return Database::getRepo('\XLite\Model\Config')
                ->findByCategoryAndVisible($this->getOptionsCategory());
        });
    }

    /**
     * Get options category (see install.yaml)
     *
     * @return string
     */
    protected function getOptionsCategory() : string
    {
        return 'PureClarity\Personalisation';
    }
}
