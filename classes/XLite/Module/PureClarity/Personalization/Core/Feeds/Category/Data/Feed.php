<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE for license details.
 */

namespace XLite\Module\PureClarity\Personalization\Core\Feeds\Category\Data;

use XLite\Base\Singleton;
use XLite\Core\Database;
use XLite\Model\Category;
use XLite\Model\QueryBuilder\AQueryBuilder;
use Doctrine\ORM\QueryBuilder;
use XLite\Module\PureClarity\Personalization\Core\Feeds\FeedDataInterface;

/**
 * class Feed
 *
 * PureClarity Category Feed Data class
 */
class Feed extends Singleton implements FeedDataInterface
{
    /**
     * Returns all completed orders in the last 12 months
     *
     * @return int
     */
    public function getFeedCount() : int
    {
        return Database::getRepo('XLite\Model\Category')->count(
            [
                'enabled' => '1',
                'pureclarityExcludeFromFeed' => '0'
            ]
        );
    }

    /**
     * Returns all completed orders in the last 12 months
     *
     * @return Category[]
     */
    public function getFeedData(int $page, int $pageSize) : array
    {
        return Database::getRepo('XLite\Model\Category')->findBy(
            [
                'enabled' => '1',
                'pureclarityExcludeFromFeed' => '0'
            ],
            null,
            $pageSize,
            ($page - 1) * $pageSize
        );
    }

    /**
     * Page cleanup
     */
    public function cleanPage() : void
    {
        // This query doesnt use direct queryies, so cleanup not possible
    }
}
