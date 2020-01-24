<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalization\Controller\Customer;

use Exception;
use XLite\Core\Event;
use XLite\Model\Order;
use XLite\Module\PureClarity\Personalization\Core\PureClarity;

/**
 * Class ACustomer
 *
 * Decorator to \XLite\Controller\Customer\Cart
 *
 * Adds PureClarity event dispatch on cart update, delete & clear
 * so that set_basket can be called on the frontend with full cart info
 */
abstract class Cart extends \XLite\Controller\Customer\Cart implements \XLite\Base\IDecorator
{
    /**
     * Triggers PureClarity event to update cart after cart is updated
     *
     * @param boolean $silent
     *
     * @throws Exception
     */
    protected function updateCart($silent = false)
    {
        if (PureClarity::getInstance()->isActive()) {
            $cart = $this->getCart();
            $initialCartFingerprint = $cart->getEventFingerprint($this->getCartFingerprintExclude());
        }

        parent::updateCart($silent);

        if (PureClarity::getInstance()->isActive()) {
            $diff = $this->getCartFingerprintDifference(
                $initialCartFingerprint,
                $cart->getEventFingerprint($this->getCartFingerprintExclude())
            );

            if ($diff) {
                $this->triggerPcCartUpdated($cart);
            }
        }
    }

    /**
     * Triggers PureClarity event to update cart after item is deleted
     */
    protected function doActionDelete()
    {
        parent::doActionDelete();

        if (PureClarity::getInstance()->isActive()) {
            $this->triggerPcCartUpdated($this->getCart());
        }
    }



    /**
     * Triggers PureClarity event to update cart after cart is cleared
     */
    protected function doActionClear()
    {
        parent::doActionClear();

        if (PureClarity::getInstance()->isActive()) {
            $this->triggerPcCartUpdated($this->getCart());
        }
    }

    /**
     * Triggers PureClarity event to update cart
     *
     * @param Order $cart
     */
    protected function triggerPcCartUpdated($cart)
    {
        $items = [];

        foreach ($cart->getItems() as $item) {
            $itemData = [
                'id' => $item->getProductId(),
                'qty' => $item->getAmount(),
                'unitprice' => $item->getItemPrice()
            ];

            if ($item->getVariant()) {
                $variant = $item->getVariant();
                $itemData['children'] = [
                    [
                        'sku' => $variant->getSku() ?: $variant->getVariantId(),
                        'qty' => $item->getAmount()
                    ]
                ];
            }

            $items[] = $itemData;
        }

        Event::pcCartUpdated(
            ['items' => $items]
        );
    }
}
