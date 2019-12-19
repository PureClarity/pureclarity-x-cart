<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\Controller\Admin;

use PureClarity\Api\Feed\Feed;
use XLite\Controller\Admin\AAdmin;
use XLite\Core\Converter;
use XLite\Core\Request;
use XLite\Core\Session;
use XLite\Core\TopMessage;
use XLite\Module\PureClarity\Personalisation\Core\Dashboard\State;
use XLite\Module\PureClarity\Personalisation\Core\Feeds\Status;
use XLite\Module\PureClarity\Personalisation\Core\Signup\Process;
use XLite\Module\PureClarity\Personalisation\Core\Signup\Request as SignupRequest;

/**
 * PureClarity Dashboard Page
 */
class PureclarityDashboard extends AAdmin
{
    /**
     * Return the current page title
     *
     * @return string
     */
    public function getTitle()
    {
        return static::t('PureClarity Dashboard');
    }
    
    /**
     * Return the current page title
     *
     * @return string
     */
    public function getDashboardState()
    {
        $state = State::getInstance();
        return $state->getState();
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return '1.0.0';
    }

    /**
     * @return string
     */
    public function getProductFeedStatusClass()
    {
        $feedStatus = Status::getInstance();
        $productFeed = $feedStatus->getFeedStatus(Feed::FEED_TYPE_PRODUCT);
        return $productFeed['class'];
    }

    /**
     * @return string
     */
    public function getProductFeedStatusLabel()
    {
        $feedStatus = Status::getInstance();
        $productFeed = $feedStatus->getFeedStatus(Feed::FEED_TYPE_PRODUCT);
        return $productFeed['label'];
    }

    /**
     * @return string
     */
    public function getCategoryFeedStatusClass()
    {
        $feedStatus = Status::getInstance();
        $productFeed = $feedStatus->getFeedStatus(Feed::FEED_TYPE_CATEGORY);
        return $productFeed['class'];
    }

    /**
     * @return string
     */
    public function getCategoryFeedStatusLabel()
    {
        $feedStatus = Status::getInstance();
        $productFeed = $feedStatus->getFeedStatus(Feed::FEED_TYPE_CATEGORY);
        return $productFeed['label'];
    }

    /**
     * @return string
     */
    public function getBrandFeedStatusClass()
    {
        $feedStatus = Status::getInstance();
        $productFeed = $feedStatus->getFeedStatus(Feed::FEED_TYPE_BRAND);
        return $productFeed['class'];
    }

    /**
     * @return string
     */
    public function getBrandFeedStatusLabel()
    {
        $feedStatus = Status::getInstance();
        $productFeed = $feedStatus->getFeedStatus(Feed::FEED_TYPE_BRAND);
        return $productFeed['label'];
    }

    /**
     * @return string
     */
    public function getUserFeedStatusClass()
    {
        $feedStatus = Status::getInstance();
        $productFeed = $feedStatus->getFeedStatus(Feed::FEED_TYPE_USER);
        return $productFeed['class'];
    }

    /**
     * @return string
     */
    public function getUserFeedStatusLabel()
    {
        $feedStatus = Status::getInstance();
        $productFeed = $feedStatus->getFeedStatus(Feed::FEED_TYPE_USER);
        return $productFeed['label'];
    }

    /**
     * @return string
     */
    public function getOrdersFeedStatusClass()
    {
        $feedStatus = Status::getInstance();
        $productFeed = $feedStatus->getFeedStatus(Feed::FEED_TYPE_ORDER);
        return $productFeed['class'];
    }

    /**
     * @return string
     */
    public function getOrdersFeedStatusLabel()
    {
        $feedStatus = Status::getInstance();
        $productFeed = $feedStatus->getFeedStatus(Feed::FEED_TYPE_ORDER);
        return $productFeed['label'];
    }

    /**
     * @return string
     */
    public function getAreFeedsInProgress()
    {
        $feedStatus = Status::getInstance();
        return $feedStatus->getAreFeedsInProgress([
            Feed::FEED_TYPE_PRODUCT,
            Feed::FEED_TYPE_CATEGORY,
            Feed::FEED_TYPE_USER,
            Feed::FEED_TYPE_BRAND,
            Feed::FEED_TYPE_ORDER
        ]);
    }

    /**
     * Get redirect form URL
     *
     * @return string
     */
    public function getConfigureUrl()
    {
        return $this->buildURL('pureclarity_settings', '', []);
    }

    /**
     * Links an existing account
     */
    public function doActionLinkAccount()
    {
        $request = Request::getInstance();

        $processor = Process::getInstance();

        $response = $processor->processManualConfigure([
            'access_key' => $request->access_key,
            'secret_key' => $request->secret_key,
            'region' => $request->region
        ]);

        if ($response['errors']) {
            $error = implode(',', $response['errors']);
            TopMessage::getInstance()->add($error);
        }

        $this->setReturnURL(
            $this->buildURL('pureclarity_dashboard', '', [])
        );
    }

    /**
     * Does a signup request
     */
    public function doActionSignup()
    {
        $request = Request::getInstance();

        $time = new \DateTime('now', Converter::getTimeZone());

        $params = [
            'firstname' => $request->first_name,
            'lastname' => $request->last_name,
            'email' => $request->email,
            'company' => $request->company,
            'password' => $request->password,
            'url' => $request->url,
            'store_name' => $request->store_name,
            'region' => $request->region,
            'currency' => \XLite::getInstance()->getCurrency()->getCode(),
            'timezone' => $time->getTimezone()->getName(),
            'platform' => 'magento2'
        ];
        
        $signupSubmit = SignupRequest::getInstance();
        $result = $signupSubmit->sendRequest($params);

        if ($result['error']) {
            $error = $result['error'];
            TopMessage::getInstance()->add($error);
            Session::getInstance()->pcSignupParams = $params;
        }

        $this->setReturnURL(
            $this->buildURL('pureclarity_dashboard', '', array())
        );
    }
}
