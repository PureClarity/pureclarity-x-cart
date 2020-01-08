<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\View\Customer\Events;

use XLite\Core\Database;
use XLite\Core\Request;
use XLite\Model\Cart;
use XLite\Model\OrderItem;
use XLite\Module\PureClarity\Personalisation\Core\PureClarity;
use XLite\View\AView;

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
        return 'modules/PureClarity/Personalisation/events/order.twig';
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
        $orderId = Request::getInstance()->order_number;

        if ($orderId) {
            /** @var Cart $order */
            $order = Database::getRepo('XLite\Model\Order')
                ->findOneByOrderNumber($orderId);

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
                        'orderid' => $order->getOrderId(),
                        'refid' => $item->getItemId(),
                        'id' => $item->getProductId(),
                        'qty' => $item->getAmount(),
                        'unitprice' => $item->getPrice(),
                        'children' => []
                    ];
                }

                $orderData['items'] = array_values($orderItems);
            }
        }

        return json_encode($orderData);
    }
}