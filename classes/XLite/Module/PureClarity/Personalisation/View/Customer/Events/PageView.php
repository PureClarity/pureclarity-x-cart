<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalization\View\Customer\Events;

use XLite\Core\Request;
use XLite\Module\PureClarity\Personalization\Core\PureClarity;
use XLite\View\AView;

/**
 * PureClarity Page View event class
 * Embeds Page View event js on every page, with added context data
 *
 * @ListChild (list="head", zone="customer", weight="105")
 */
class PageView extends AView
{
    /**
     * Return widget template
     *
     * @return string
     */
    protected function getDefaultTemplate() : string
    {
        return 'modules/PureClarity/Personalization/events/page_view.twig';
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
    protected function getPageViewContext() : string
    {
        $pageType = $this->getPageType();
        $context = $this->getPageContext($pageType);
        return json_encode($context);
    }

    /**
     * Works out what page type this page is, based on the target
     *
     * @return string
     */
    protected function getPageType() : string
    {
        $pageType = '';
        switch (Request::getInstance()->target) {
            case 'main':
                $pageType = 'homepage';
                break;
            case 'category':
                $pageType = 'category_listing_page';
                break;
            case 'product':
                $pageType = 'product_page';
                break;
            case 'cart':
                $pageType = 'basket_page';
                break;
            case 'profile':
                $pageType = 'my_account';
                break;
            case 'checkoutSuccess':
                $pageType = 'order_complete_page';
                break;
            case 'search':
                $pageType = 'search_results';
                break;
            case 'page':
                $pageType = 'content_page';
                break;
        }

        return $pageType;
    }

    /**
     * Gets page-type specific context (e.g. product page - product ID)
     *
     * @param string $pageType
     *
     * @return array
     */
    protected function getPageContext(string $pageType) : array
    {
        $context = [];

        if ($pageType) {
            $context['page_type'] = $pageType;
        }

        switch ($pageType) {
            case 'category_listing_page':
                $context['category_id'] = Request::getInstance()->category_id;
                break;
            case 'product_page':
                $context['product_id'] = Request::getInstance()->product_id;
                $context['category_id'] = Request::getInstance()->category_id;
                break;
        }

        return $context;
    }
}
