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
     * Returns all completed orders in the last 12 months
     *
     * @return Order[]
     */
    public function getFeedData() : array
    {
        return Database::getRepo('XLite\Model\Order')->createQueryBuilder('o')
            ->andWhere('o.date >= :start')
            ->setParameter('start', strtotime("-12 month"))
            ->andWhere('o.paymentStatus = 4')
            ->andWhere('o.shippingStatus = 4')
            ->getResult();
    }
}
