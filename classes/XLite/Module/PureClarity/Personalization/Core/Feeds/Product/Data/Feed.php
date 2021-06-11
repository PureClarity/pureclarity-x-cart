<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE for license details.
 */

namespace XLite\Module\PureClarity\Personalization\Core\Feeds\Product\Data;

use XLite\Base\Singleton;
use XLite\Core\Database;
use XLite\Model\Product;
use XLite\Module\PureClarity\Personalization\Core\Feeds\FeedDataInterface;
use XLite\Module\PureClarity\Personalization\Core\PureClarity;
use Doctrine\ORM\QueryBuilder;

/**
 * class Feed
 *
 * PureClarity Product Feed Data class
 */
class Feed extends Singleton implements FeedDataInterface
{
    /**
     * Returns a count of the products about to be sent in the feed.
     *
     * @return int
     */
    public function getFeedCount() : int
    {
        $qb = $this->getQuery();
        return (int)$qb->count();
    }

    /**
     * Returns a page of product data
     *
     * @param int $page
     * @param int $pageSize
     *
     * @return Product[]
     */
    public function getFeedData(int $page, int $pageSize) : array
    {
        $qb = $this->getQuery();
        $qb->setFirstResult(($page - 1) * $pageSize);
        $qb->setMaxResults($pageSize);
        return $qb->getResult();
    }

    /**
     * Page cleanup
     *
     * Clears query entity manager's object cache.
     */
    public function cleanPage() : void
    {
        $qb = $this->getQuery();
        $qb->getQuery()->getEntityManager()->clear();
    }

    /**
     * Builds a query to return all enabled products that need to be sent in the feed
     *
     * May also exclude out of stock products, if configured to do so
     *
     * @return QueryBuilder
     */
    private function getQuery()
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

        return $qb;
    }
}
