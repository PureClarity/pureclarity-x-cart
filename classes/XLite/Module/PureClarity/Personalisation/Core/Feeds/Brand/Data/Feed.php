<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\Core\Feeds\Brand\Data;

use XLite\Base\Singleton;
use XLite\Core\Database;
use XLite\Model\Category;
use XLite\Module\PureClarity\Personalisation\Core\Feeds\FeedDataInterface;
use XLite\Module\PureClarity\Personalisation\Core\Pureclarity;

/**
 * class Feed
 *
 * PureClarity Product Brand Feed Data class
 */
class Feed extends Singleton implements FeedDataInterface
{
    /**
     * Returns all Categories that are immediate children of the category selected to be the Brand parent category
     *
     * @return Category[]
     */
    public function getFeedData() : array
    {
        return Database::getRepo('XLite\Model\Category')->findBy([
            'parent' => Pureclarity::getInstance()->getConfig(Pureclarity::CONFIG_FEEDS_BRAND_PARENT)
        ]);
    }
}
