<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\Controller\Customer;

use XLite\Module\PureClarity\Personalisation\Core\Pureclarity;

abstract class ACustomer extends \XLite\Controller\Customer\ACustomer implements \XLite\Base\IDecorator
{
    /**
     * Recalculates the shopping cart
     *
     * @param boolean $silent
     *
     * @throws \Exception
     */
    protected function updateCart($silent = false)
    {
        parent::updateCart($silent);

        if (Pureclarity::getInstance()->isActive()) {
            $cart = $this->getCart();

            $items = [];

            foreach ($cart->getItems() as $item) {
                $items[] = [
                    'id' => $item->getProductId(),
                    'qty' => $item->getAmount(),
                    'unitprice' => $item->getItemPrice()
                ];
            }

            \XLite\Core\Event::pcCartUpdated(
                ['items' => $items]
            );
        }
    }
}
