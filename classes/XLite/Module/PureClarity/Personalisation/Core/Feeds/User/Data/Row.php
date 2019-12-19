<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\Core\Feeds\User\Data;

use XLite\Base\Singleton;
use XLite\Model\Profile;
use XLite\Module\PureClarity\Personalisation\Core\Feeds\FeedRowDataInterface;

/**
 * class Row
 *
 * PureClarity User Feed Row Data class
 */
class Row extends Singleton implements FeedRowDataInterface
{
    /**
     * @param object|Profile $row
     *
     * @return mixed[]|null
     */
    public function getRowData($row) : array
    {
        $address = $row->getBillingAddress();

        return [
            'UserId' => (string)$row->getProfileId(),
            'GroupId' => $row->getMembershipId(),
            'Email' => $row->getEmail(),
            'FirstName' => $address ? $address->getFirstname() : '',
            'LastName' => $address ? $address->getLastname() : '',
            'City' => $address ? $address->getCity() : '',
            'State' => $address ? $address->getStateName() : '',
            'Country' => $address ? $address->getCountryCode() : ''
        ];
    }
}
