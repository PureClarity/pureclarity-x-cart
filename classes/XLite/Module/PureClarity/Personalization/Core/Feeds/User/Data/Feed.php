<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE for license details.
 */

namespace XLite\Module\PureClarity\Personalization\Core\Feeds\User\Data;

use XLite\Base\Singleton;
use XLite\Core\Database;
use XLite\Model\QueryBuilder\AQueryBuilder;
use XLite\Module\PureClarity\Personalization\Core\Feeds\FeedDataInterface;

/**
 * class Feed
 *
 * PureClarity User Feed Data class
 */
class Feed extends Singleton implements FeedDataInterface
{
    /**
     * Returns a count of all registered, active users
     *
     * @return int
     */
    public function getFeedCount() : int
    {
        // Simpler version of the query in getFeedData, to give a count of active users
        $query = Database::getRepo('XLite\Model\Profile')->createQueryBuilder();
        $query->setCacheable(false);
        $query->andWhere('p.anonymous != 1');
        $query->andWhere('p.last_login >= :start');
        $query->andWhere('p.status = :status');
        $query->setParameter('status', 'E');
        $query->setParameter('start', strtotime("-3 month"));
        return $query->count();
    }

    /**
     * Page Cleaning
     */
    public function cleanPage() : void
    {
        // the query used doesnt use an object hydrator, so no clean is needed.
    }

    /**
     * Returns a page of registered, active users
     *
     * @param int $page
     * @param int $pageSize
     * @return array
     */
    public function getFeedData(int $page, int $pageSize) : array
    {
        $query = Database::getRepo('XLite\Model\Profile')->createQueryBuilder();

        // make the query only select users who are enabled, not anonymous and have logged in during the last month
        $query->andWhere('p.anonymous != 1');
        $query->andWhere('p.last_login >= :start');
        $query->setParameter('start', strtotime("-1 month"));
        $query->andWhere('p.status = :status');
        $query->setParameter('status', 'E');

        // Join membership so we can get membership ID for the GroupID
        $query->linkLeft('p.membership', 'membership');
        $query->addSelect('membership.membership_id');

        // Join addresses sow e can get the address info
        $query->linkLeft('p.addresses');
        $this->addAddressField($query, 'firstname');
        $this->addAddressField($query, 'lastname');
        $this->addAddressField($query, 'state_id');
        $this->addAddressField($query, 'city');
        $this->addAddressField($query, 'country_code');
        $this->addAddressField($query, 'custom_state');

        // Group by profile ID so that we don't get multiple rows per user due to the joins
        $query->groupBy('p.profile_id');

        // set the page size
        $query->setFirstResult(($page - 1) * $pageSize);
        $query->setMaxResults($pageSize);

        return $query->getArrayResult();
    }

    /**
     * Adds a left join to address field data.
     *
     * @param AQueryBuilder $query
     * @param string $fieldName
     */
    private function addAddressField(&$query, string $fieldName)
    {
        $addressFieldName = 'address_field_value_' . $fieldName;

        $addressField = Database::getRepo('XLite\Model\AddressField')
            ->findOneBy(array('serviceName' => $fieldName));

        $query->linkLeft(
            'addresses.addressFields',
            $addressFieldName,
            \Doctrine\ORM\Query\Expr\Join::WITH,
            $addressFieldName . '.addressField = :' . $fieldName
        )->setParameter($fieldName, $addressField);

        $query->addSelect($addressFieldName . '.value as ' . $fieldName);
    }
}
