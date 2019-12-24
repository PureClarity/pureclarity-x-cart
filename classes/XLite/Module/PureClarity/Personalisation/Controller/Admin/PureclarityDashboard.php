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
use XLite\Module\PureClarity\Personalisation\Main;
use XLite\Module\PureClarity\Personalisation\Core\State as PureClarityState;

/**
 * Class PureclarityDashboard
 *
 * PureClarity Dashboard Page Controller
 */
class PureclarityDashboard extends AAdmin
{
    /**
     * Return the current page title
     *
     * @return string
     */
    public function getTitle() : string
    {
        return static::t('PureClarity Dashboard');
    }
    
    /**
     * Return the current page title
     *
     * @return string
     */
    public function getDashboardState() : string
    {
        $state = State::getInstance();
        return $state->getState();
    }

    /**
     * Return the current page title
     *
     * @return string
     */
    public function getCronState() : string
    {
        $state = PureClarityState::getInstance();
        return $state->getStateValue('has_cron_run');
    }

    /**
     * Returns the current version of the Module
     *
     * @return string
     */
    public function getVersion() : string
    {
        return Main::getVersion();
    }

    /**
     * Returns the class to use for the Product feed status display
     *
     * @return string
     */
    public function getProductFeedStatusClass() : string
    {
        $feedStatus = Status::getInstance();
        $productFeed = $feedStatus->getFeedStatus(Feed::FEED_TYPE_PRODUCT);
        return $productFeed['class'];
    }

    /**
     * Returns the label to use for the Product feed status display
     *
     * @return string
     */
    public function getProductFeedStatusLabel() : string
    {
        $feedStatus = Status::getInstance();
        $productFeed = $feedStatus->getFeedStatus(Feed::FEED_TYPE_PRODUCT);
        return $productFeed['label'];
    }

    /**
     * Returns the class to use for the Category feed status display
     *
     * @return string
     */
    public function getCategoryFeedStatusClass() : string
    {
        $feedStatus = Status::getInstance();
        $productFeed = $feedStatus->getFeedStatus(Feed::FEED_TYPE_CATEGORY);
        return $productFeed['class'];
    }

    /**
     * Returns the label to use for the Category feed status display
     *
     * @return string
     */
    public function getCategoryFeedStatusLabel() : string
    {
        $feedStatus = Status::getInstance();
        $productFeed = $feedStatus->getFeedStatus(Feed::FEED_TYPE_CATEGORY);
        return $productFeed['label'];
    }

    /**
     * Returns the class to use for the Brand feed status display
     *
     * @return string
     */
    public function getBrandFeedStatusClass() : string
    {
        $feedStatus = Status::getInstance();
        $productFeed = $feedStatus->getFeedStatus(Feed::FEED_TYPE_BRAND);
        return $productFeed['class'];
    }

    /**
     * Returns the label to use for the Brand feed status display
     *
     * @return string
     */
    public function getBrandFeedStatusLabel() : string
    {
        $feedStatus = Status::getInstance();
        $productFeed = $feedStatus->getFeedStatus(Feed::FEED_TYPE_BRAND);
        return $productFeed['label'];
    }

    /**
     * Returns the class to use for the User feed status display
     *
     * @return string
     */
    public function getUserFeedStatusClass() : string
    {
        $feedStatus = Status::getInstance();
        $productFeed = $feedStatus->getFeedStatus(Feed::FEED_TYPE_USER);
        return $productFeed['class'];
    }

    /**
     * Returns the label to use for the User feed status display
     *
     * @return string
     */
    public function getUserFeedStatusLabel() : string
    {
        $feedStatus = Status::getInstance();
        $productFeed = $feedStatus->getFeedStatus(Feed::FEED_TYPE_USER);
        return $productFeed['label'];
    }

    /**
     * Returns the class to use for the Order feed status display
     *
     * @return string
     */
    public function getOrdersFeedStatusClass() : string
    {
        $feedStatus = Status::getInstance();
        $productFeed = $feedStatus->getFeedStatus(Feed::FEED_TYPE_ORDER);
        return $productFeed['class'];
    }

    /**
     * Returns the label to use for the Order feed status display
     *
     * @return string
     */
    public function getOrdersFeedStatusLabel() : string
    {
        $feedStatus = Status::getInstance();
        $productFeed = $feedStatus->getFeedStatus(Feed::FEED_TYPE_ORDER);
        return $productFeed['label'];
    }

    /**
     * Returns whether the PureClarity feeds are currently in progress
     *
     * @return bool
     */
    public function getAreFeedsInProgress() : bool
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
    public function getConfigureUrl() : string
    {
        return $this->buildURL('pureclarity_settings', '', []);
    }

    /**
     * Links the module to an existing account
     */
    public function doActionLinkAccount() : void
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
     * Sends a signup request to PureClarity
     */
    public function doActionSignup() : void
    {
        $request = Request::getInstance();

        try {
            $time = new \DateTime('now', Converter::getTimeZone());
            $timeZone = $time->getTimezone()->getName();
            if ($timeZone === 'UTC') {
                $timeZone = 'Europe\London';
            }
        } catch (\Exception $e) {
            $timeZone = 'Europe\London';
        }

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
            'timezone' => $timeZone,
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
