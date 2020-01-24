<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalization\Core\Feeds\Product\Data;

use XLite\Base\Singleton;
use XLite\Core\Database;
use XLite\Model\Product;
use XLite\Module\PureClarity\Personalization\Core\Feeds\FeedDataInterface;
use XLite\Module\PureClarity\Personalization\Core\PureClarity;

/**
 * class Feed
 *
 * PureClarity Product Feed Data class
 */
class Feed extends Singleton implements FeedDataInterface
{
    /**
     * Returns all enabled products that need to be sent in the feed
     *
     * May also exclude out of stock products, if configured to do so
     *
     * @return Product[]
     */
    public function getFeedData() : array
    {
        $pc = PureClarity::getInstance();
        $excludeOutOfStock = $pc->getConfigFlag(PureClarity::CONFIG_FEEDS_PRODUCT_OOS_EXCLUDE);

        $qb = Database::getRepo('XLite\Model\Product')->createQueryBuilder();
        $alias = $qb->getMainAlias();

        $qb->where(
            $alias . '.enabled = true'
        );

        if ($excludeOutOfStock) {
            $qb->andWhere(
                '(' . $alias . '.inventoryEnabled = false OR ' . $alias . '.amount > 0)'
            );
        }

        return $qb->getResult();
    }
}
