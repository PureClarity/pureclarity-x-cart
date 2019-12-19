<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\Core\Feeds\User\Data;

use XLite\Base\Singleton;
use XLite\Core\Database;
use XLite\Model\Profile;
use XLite\Module\PureClarity\Personalisation\Core\Feeds\FeedDataInterface;

/**
 * class Feed
 *
 * PureClarity User Feed Data class
 */
class Feed extends Singleton implements FeedDataInterface
{
    /**
     * Returns all registered users to be sent in the feed
     *
     * @return  Profile[]
     */
    public function getFeedData() : array
    {
        return Database::getRepo('XLite\Model\Profile')->findBy(
            [
                'anonymous' => 0
            ]
        );
    }
}
