<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE for license details.
 */

namespace XLite\Module\PureClarity\Personalization\Core\Feeds\Brand\Data;

use XLite\Base\Singleton;
use XLite\Core\Database;
use XLite\Model\Category;
use XLite\Model\QueryBuilder\AQueryBuilder;
use Doctrine\ORM\QueryBuilder;
use XLite\Module\PureClarity\Personalization\Core\Feeds\FeedDataInterface;
use XLite\Module\PureClarity\Personalization\Core\PureClarity;

/**
 * class Feed
 *
 * PureClarity Product Brand Feed Data class
 */
class Feed extends Singleton implements FeedDataInterface
{
    /**
     * Returns a count of all brand categories.
     *
     * @return int
     */
    public function getFeedCount() : int
    {
        return $this->getQuery()->count();
    }

    /**
     * Returns a page for the brand feed.
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
     * Returns all Categories that are immediate children of the category selected to be the Brand parent category
     *
     * @return QueryBuilder|AQueryBuilder
     */
    public function getQuery()
    {
        return Database::getRepo('XLite\Model\Category')->createQueryBuilder('c')
            ->andWhere('c.parent = :parent')
            ->setParameter('parent', PureClarity::getInstance()->getConfig(PureClarity::CONFIG_FEEDS_BRAND_PARENT));
    }
}
