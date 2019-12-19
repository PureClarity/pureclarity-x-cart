<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\Controller\Admin;

use XLite\Controller\Admin\AAdmin;

/**
 * PureClarity Dashboard Page
 */
class PureclaritySignup extends AAdmin
{
    /**
     * Return the current page title
     *
     * @return string
     */
    public function getTitle()
    {
        return static::t('Account Setup');
    }
}
