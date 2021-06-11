<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE for license details.
 */

namespace XLite\Module\PureClarity\Personalization\Core\Feeds\Order\Data;

use XLite\Base\Singleton;
use XLite\Core\Database;
use XLite\Model\Order;
use XLite\Module\PureClarity\Personalization\Core\Feeds\FeedDataInterface;

/**
 * class Feed
 *
 * PureClarity Order Feed Data class
 */
class Feed extends Singleton implements FeedDataInterface
{
    /**
     * Returns a count of orders for the feed
     *
     * @return int
     */
    public function getFeedCount() : int
    {
        return $this->getQuery()->count();
    }

    /**
     * Returns a page of order feed data
     *
     * @return Order[]
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
     * @return \Doctrine\ORM\QueryBuilder|\XLite\Model\QueryBuilder\AQueryBuilder
     */
    public function getQuery()
    {
        return Database::getRepo('XLite\Model\Order')->createQueryBuilder('o')
            ->andWhere('o.date >= :start')
            ->setParameter('start', strtotime("-12 month"))
            ->andWhere('o.paymentStatus = 4')
            ->andWhere('o.shippingStatus = 4');
    }
}
