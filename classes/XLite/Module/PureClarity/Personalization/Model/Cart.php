<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE for license details.
 */

namespace XLite\Module\PureClarity\Personalization\Model;

use XLite\Core\Event;
use XLite\Core\Session;
use XLite\Model\Profile;
use XLite\Module\PureClarity\Personalization\Core\PureClarity;

abstract class Cart extends \XLite\Model\Cart implements \XLite\Base\IDecorator
{
    /**
     * Login operation
     *
     * @param Profile $profile Profile
     *
     * @return void
     */
    public function login(Profile $profile)
    {
        parent::login($profile);

        if (PureClarity::getInstance()->isActive()) {
            $address = $profile->getBillingAddress();

            Event::pcLogin(
                [
                    'userid' => (string)$profile->getProfileId(),
                    'email' => $profile->getEmail(),
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

        if (PureClarity::getInstance()->isActive()) {
            Session::getInstance()->pc_logoff = true;
        }
    }
}
