<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE for license details.
 */

namespace XLite\Module\PureClarity\Personalization\Controller\Admin;

use XLite\Controller\Admin\AAdmin;

/**
 * Class PureclaritySignup
 *
 * PureClarity Signup form Page Controller
 */
class PureclaritySignup extends AAdmin
{
    /**
     * Return the current page title
     *
     * @return string
     */
    public function getTitle() : string
    {
        return static::t('Account Setup');
    }
}
