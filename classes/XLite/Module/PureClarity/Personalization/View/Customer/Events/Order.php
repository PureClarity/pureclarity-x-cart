<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE for license details.
 */

namespace XLite\Module\PureClarity\Personalization\View\Customer\Events;

use XLite\Core\Database;
use XLite\Core\Request;
use XLite\Model\Cart;
use XLite\Model\OrderItem;
use XLite\Module\PureClarity\Personalization\Core\PureClarity;
use XLite\View\AView;
use XLite\Logger;

/**
 * PureClarity Order event class
 * Embeds Order event js on order success page
 *
 * @ListChild (list="head", zone="customer", weight="103")
 */
class Order extends AView
{
    /**
     * Adds checkoutSuccess as an allowed target, so it only displays on that target
     *
     * @return array
     */
    public static function getAllowedTargets() : array
    {
        $result = parent::getAllowedTargets();
        $result[] = 'checkoutSuccess';

        return $result;
    }

    /**
     * Return widget template
     *
     * @return string
     */
    protected function getDefaultTemplate() : string
    {
        return 'modules/PureClarity/Personalization/events/order.twig';
    }

    /**
     * Returns whether this Widget should be visible or not
     *
     * @return bool
     */
    protected function isVisible() : bool
    {
        return PureClarity::getInstance()->isActive();
    }

    /**
     * @return string
     */
    protected function getOrderEventData() : string
    {
        $orderData = [];
        $order = null;
        if (Request::getInstance()->order_id) {
            $order = Database::getRepo('XLite\Model\Order')
                ->find((int) Request::getInstance()->order_id);
        } elseif (Request::getInstance()->order_number) {
            $order = Database::getRepo('XLite\Model\Order')
                ->findOneByOrderNumber(Request::getInstance()->order_number);
        }
        
        /** @var Cart $order */
        if ($order) {
            $address = $order->getProfile()->getBillingAddress();
            $orderData = [
                'orderid' => $order->getOrderId(),
                'firstname' => $address ? $address->getFirstname() : '',
                'lastname' => $address ? $address->getLastname() : '',
                'postcode' => $address ? $address->getZipcode() : '',
                'userid' => $order->getProfile()->getProfileId(),
                'ordertotal' => $order->getTotal(),
                'email' => $order->getProfile()->getEmail()
            ];

            $orderItems = [];
            $items = $order->getItems();
            foreach ($items as $item) {
                /** @var OrderItem $item */
                $orderItems[$item->getItemId()] = [
                    'id' => $item->getProductId(),
                    'qty' => $item->getAmount(),
                    'unitprice' => $item->getPrice(),
                    'children' => []
                ];

                if ($variant = $item->getVariant()) {
                    $orderItems[$item->getItemId()]['children'] = [
                        [
                            'sku' => $variant->getSku() ?: $variant->getVariantId(),
                            'qty' => $item->getAmount()
                        ]
                    ];
                }
            }

            $orderData['items'] = array_values($orderItems);
        } else {
            Logger::logCustom('pureclarity', 'Order tracking error, no order found', false);
        }

        return json_encode($orderData);
    }
}
