<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\View\Customer\Events;

use XLite\Core\Request;
use XLite\Module\PureClarity\Personalisation\Core\PureClarity;
use XLite\View\AView;

/**
 * PureClarity Page View event class
 * Embeds Page View event js on every page, with added context data
 *
 * @ListChild (list="head", zone="customer", weight="106")
 */
class ProductView extends AView
{
    /**
     * Adds checkoutSuccess as an allowed target, so it only displays on that target
     *
     * @return array
     */
    public static function getAllowedTargets() : array
    {
        $result = parent::getAllowedTargets();
        $result[] = 'product';

        return $result;
    }

    /**
     * Return widget template
     *
     * @return string
     */
    protected function getDefaultTemplate() : string
    {
        return 'modules/PureClarity/Personalisation/events/product_view.twig';
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
     * Returns an encoded array of context data for the Page View event
     *
     * @return string
     */
    protected function getProductViewContext() : string
    {
        $context = [
            'product_id' => Request::getInstance()->product_id,
            'category_id' => Request::getInstance()->category_id
        ];

        return json_encode($context);
    }
}
