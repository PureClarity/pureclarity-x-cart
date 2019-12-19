<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\Core\Feeds\Order\Data;

use XLite\Base\Singleton;
use XLite\Model\Order;
use XLite\Module\PureClarity\Personalisation\Core\Feeds\FeedRowDataInterface;

/**
 * class Row
 *
 * PureClarity Order Feed Row Data class
 */
class Row extends Singleton implements FeedRowDataInterface
{
    /**
     * @param object|Order $row
     *
     * @return mixed[]|null
     */
    public function getRowData($row) : array
    {
        $orderData = [];

        foreach ($row->getItems() as $orderItem) {
            $orderData[] = [
                'OrderID' => $row->getOrderId(),
                'UserId' => $row->getProfile()->getProfileId() ?: '',
                'Email' => $row->getProfile()->getEmail(),
                'DateTime' => date('Y-m-d H:i:s', $row->getDate()),
                'ProdCode' => $orderItem->getSku(),
                'Quantity' => $orderItem->getAmount(),
                'UnitPrice' => $orderItem->getPrice(),
                'LinePrice' => $orderItem->getSubtotal()
            ];
        }

        return $orderData;
    }
}
