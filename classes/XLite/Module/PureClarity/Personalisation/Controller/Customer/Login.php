<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\Controller\Customer;

use XLite\Module\PureClarity\Personalisation\Core\Pureclarity;

abstract class Login extends \XLite\Controller\Customer\Login implements \XLite\Base\IDecorator
{
    /**
     * Log out
     *
     * @return void
     */
    protected function doActionLogoff()
    {
        parent::doActionLogoff();
        if (Pureclarity::getInstance()->isActive()) {
            \XLite\Core\Event::pcLogout();
        }
    }
}
