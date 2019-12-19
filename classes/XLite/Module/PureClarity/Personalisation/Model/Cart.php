<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\Model;

use XLite\Module\PureClarity\Personalisation\Core\Pureclarity;

abstract class Cart extends \XLite\Model\Cart implements \XLite\Base\IDecorator
{
    /**
     * Login operation
     *
     * @param \XLite\Model\Profile $profile Profile
     *
     * @return void
     */
    public function login(\XLite\Model\Profile $profile)
    {
        parent::login($profile);

        if (Pureclarity::getInstance()->isActive()) {
            $address = $profile->getBillingAddress();

            \XLite\Core\Event::pcLogin(
                [
                    'userid' => (string)$profile->getProfileId(),
                    'email' => $profile->getEmail(),
                    'accid' => '',
                    'groupid' => $profile->getMembershipId(),
                    'firstname' => $address ? $address->getFirstname() : '',
                    'lastname' => $address ? $address->getLastname() : '',
                ]
            );
        }
    }

    /**
     * Logoff operation
     *
     * @return void
     */
    public function logoff()
    {
        parent::logoff();

        if (Pureclarity::getInstance()->isActive()) {
            \XLite\Core\Session::getInstance()->pc_logoff = 'customer';
        }
    }
}
