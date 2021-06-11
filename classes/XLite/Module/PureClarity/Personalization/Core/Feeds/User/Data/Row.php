<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE for license details.
 */

namespace XLite\Module\PureClarity\Personalization\Core\Feeds\User\Data;

use XLite\Base\Singleton;
use XLite\Module\PureClarity\Personalization\Core\Feeds\FeedRowDataInterface;

/**
 * class Row
 *
 * PureClarity User Feed Row Data class
 */
class Row extends Singleton implements FeedRowDataInterface
{
    /**
     * Processes the provided User into an array in the format required for the PureClarity User Feed
     *
     * Note - data format is odd due to Doctrine ORM:
     * Left joined fields are directly in the array e.g. $row['city']
     * But core fields are in a sub array $row[0]['profile_id']
     *
     * @param array $row
     *
     * @return array
     */
    public function getRowData($row) : array
    {
        return [
            'UserId' => (string)$row[0]['profile_id'],
            'GroupId' => $row['membership_id'],
            'Email' => $row[0]['login'],
            'FirstName' => $row['firstname'],
            'LastName' => $row['lastname'],
            'City' => $row['city'],
            'State' => $row['custom_state'] ?: $row['state_id'],
            'Country' => $row['country_code']
        ];
    }
}
