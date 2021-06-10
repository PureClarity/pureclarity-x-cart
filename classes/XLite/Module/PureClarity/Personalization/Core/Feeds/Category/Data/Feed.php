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
        return $this->getQuery()->count();
    }

    /**
     * Returns all completed orders in the last 12 months
     *
     * @return Category[]
     */
    public function getFeedData(int $page, int $pageSize) : array
    {
        $query = $this->getQuery();
        $query->setFirstResult(($page - 1) * $pageSize);
        $query->setMaxResults($pageSize);
        return $query->getResult();
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
     * Returns a query for all completed orders in the last 12 months
     *
     * @return QueryBuilder|AQueryBuilder
     */
    public function getQuery()
    {
        return Database::getRepo('XLite\Model\Category')->createQueryBuilder('c')
            ->andWhere('c.enabled >= :enabled')
            ->setParameter('enabled', 1)
            ->andWhere('c.pureclarityExcludeFromFeed = :pureclarityExcludeFromFeed')
            ->setParameter('pureclarityExcludeFromFeed', 0);
    }
}
