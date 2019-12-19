<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\View;

use PureClarity\Api\Resource\Endpoints;
use XLite\Core\Database;
use XLite\Module\PureClarity\Personalisation\Core\Pureclarity;

/**
 * Common widget extention.
 * This widget is used only to link additional css and js files to the page
 *
 * @ListChild (list="head", zone="customer", weight="101")
 */
class Base extends \XLite\View\AView
{
    /**
     * Get a list of CSS files required to display the widget properly
     *
     * @return array
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();
        $list[] = $this->getDir() . '/js/pc.js';
        return $list;
    }

    /**
     * Get a list of CSS files required to display the widget properly
     *
     * @return array
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();
        $list[] = $this->getDir() . '/css/pc.css';
        return $list;
    }

    /**
     * Return templates directory name
     *
     * @return string
     */
    protected function getDir()
    {
        return 'modules/PureClarity/Personalisation/';
    }

    /**
     * @return string
     */
    public function getClientScriptUrl()
    {
        return Endpoints::getClientScriptUrl(Pureclarity::getInstance()->getConfig('access_key'));
    }

    /**
     * Return widget default template
     *
     * @return string
     */
    protected function getDefaultTemplate()
    {
        return 'modules/PureClarity/Personalisation/base.twig';
    }

    /**
     * Return widget default template
     *
     * @return string
     */
    protected function isVisible()
    {
        return Pureclarity::getInstance()->isActive();
    }

    /**
     * Return widget default template
     *
     * @return string
     */
    protected function getPageViewContext()
    {
        $pageType = $this->getPageType();
        $context = $this->getPageContext($pageType);
        return json_encode($context);
    }

    protected function getPageType()
    {
        $pageType = '';
        switch (\XLite\Core\Request::getInstance()->target) {
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

    protected function getPageContext($pageType)
    {
        $context = [];

        if ($pageType) {
            $context['page_type'] = $pageType;
        }

        switch ($pageType) {
            case 'category_listing_page':
                $context['category_id'] = \XLite\Core\Request::getInstance()->category_id;
                break;
            case 'product_page':
                $context['product_id'] = \XLite\Core\Request::getInstance()->product_id;
                $context['category_id'] = \XLite\Core\Request::getInstance()->category_id;
                break;
        }

        return $context;
    }

    protected function doLogoffEvent()
    {
        $logoff = \XLite\Core\Session::getInstance()->pc_logoff;
        \XLite\Core\Session::getInstance()->pc_logoff = false;
        return $logoff ? true : false;
    }

    protected function doOrderEvent()
    {
        return \XLite\Core\Request::getInstance()->target === 'checkoutSuccess';
    }

    protected function getOrderEventData()
    {
        $orderData = [];
        $orderId = \XLite\Core\Request::getInstance()->order_number;

        if ($orderId) {
            /** @var \XLite\Model\Cart $order */
            $order = \XLite\Core\Database::getRepo('XLite\Model\Order')
                ->findOneByOrderNumber(\XLite\Core\Request::getInstance()->order_number);

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
                    /** @var \XLite\Model\OrderItem $item */
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
