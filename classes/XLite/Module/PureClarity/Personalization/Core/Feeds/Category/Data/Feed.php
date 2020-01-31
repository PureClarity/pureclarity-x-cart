<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE for license details.
 */

namespace XLite\Module\PureClarity\Personalization\Core\Feeds\Category\Data;

use XLite\Base\Singleton;
use XLite\Core\Database;
use XLite\Model\Category;
use XLite\Module\PureClarity\Personalization\Core\Feeds\FeedDataInterface;

/**
 * class Feed
 *
 * PureClarity Category Feed Data class
 */
class Feed extends Singleton implements FeedDataInterface
{
    /**
     * Returns all enabled Categories and all categories that are not marked as excluded
     *
     * @return Category[]
     */
    public function getFeedData() : array
    {
        return Database::getRepo('XLite\Model\Category')->findBy(
            [
                'enabled' => '1',
                'pureclarityExcludeFromFeed' => '0'
            ]
        );
    }
}
