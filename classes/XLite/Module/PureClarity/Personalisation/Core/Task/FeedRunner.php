<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\Core\Task;

use PureClarity\Api\Feed\Feed;
use XLite\Module\PureClarity\Personalisation\Core\Feeds\Category\Runner as CategoryFeedRunner;
use XLite\Module\PureClarity\Personalisation\Core\Feeds\Product\Runner as ProductFeedRunner;
use XLite\Module\PureClarity\Personalisation\Core\Feeds\Brand\Runner as BrandFeedRunner;
use XLite\Module\PureClarity\Personalisation\Core\Feeds\User\Runner as UserFeedRunner;
use XLite\Module\PureClarity\Personalisation\Core\Feeds\Order\Runner as OrderFeedRunner;
use XLite\Module\PureClarity\Personalisation\Core\State;
use XLite\Core\Config;

/**
 * Scheduled task that checks for & sends feeds
 */
class FeedRunner extends \XLite\Core\Task\Base\Periodic
{
    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return static::t('PureClarity Feeds');
    }

    /**
     * Run step
     *
     * @return void
     */
    protected function runStep()
    {
        $this->markCronAsRun();

        $state = State::getInstance();
        $isConfigured = $state->getStateValue('is_configured');
        if ($isConfigured === '1') {
            $this->checkRequestedFeeds();
            $this->checkNightlyFeed();
        }
    }

    /**
     * Checks the has_cron_run PureClarity state, and sends it to 1 if it's not that already
     *
     * If has_cron_run not set to 1, then a warning appears on the dashboard
     */
    protected function markCronAsRun()
    {
        $state = State::getInstance();
        $hasCronRun = $state->getStateValue('has_cron_run');
        if (empty($hasCronRun)) {
            $state->setStateValue('has_cron_run', '1');
        }
    }

    /**
     * Checks for feeds manually requested via the dashboard
     */
    protected function checkRequestedFeeds()
    {
        $state = State::getInstance();
        // Run scheduled Feeds
        $feeds = $state->getStateValue('requested_feeds');
        if (!empty($feeds)) {
            $feedTypes = json_decode($feeds);
            $this->sendFeeds($feedTypes);
            $state->setStateValue('requested_feeds', '');
        }
    }

    /**
     * Checks to see if it's the right time to run the nightly feed, and if so, runs all relevant feeds
     */
    protected function checkNightlyFeed()
    {
        $nightlyFeeds = Config::getInstance()->PureClarity->Personalisation->pc_feeds_nightly;
        if ($nightlyFeeds) {
            $state = State::getInstance();
            $nightlyFeedRun = $state->getStateValue('nightly_feed_run');
            $hour = date('H');
            if ($hour === '3' && empty($nightlyFeedRun)) {
                // it's 3am, time to run the feeds
                $feedTypes = [
                    Feed::FEED_TYPE_PRODUCT,
                    Feed::FEED_TYPE_CATEGORY,
                    Feed::FEED_TYPE_BRAND,
                    Feed::FEED_TYPE_USER,
                ];
                $this->sendFeeds($feedTypes);
                $state->setStateValue('nightly_feed_run', '1');
            } elseif ($nightlyFeedRun === '1') {
                // Reset feed if not 3am, so that when 3am hits, it'll run
                $state->setStateValue('nightly_feed_run', '');
            }
        }
    }

    /**
     * Sends the feeds provided in the feedTypes array
     *
     * @param string[] $feedTypes
     */
    protected function sendFeeds($feedTypes)
    {
        if (in_array(Feed::FEED_TYPE_PRODUCT, $feedTypes)) {
            $productFeed = ProductFeedRunner::getInstance();
            $productFeed->runFeed();
        }

        if (in_array(Feed::FEED_TYPE_CATEGORY, $feedTypes)) {
            $categoryFeed = CategoryFeedRunner::getInstance();
            $categoryFeed->runFeed();
        }

        if (in_array(Feed::FEED_TYPE_BRAND, $feedTypes)) {
            $brandFeed = BrandFeedRunner::getInstance();
            $brandFeed->runFeed();
        }

        if (in_array(Feed::FEED_TYPE_USER, $feedTypes)) {
            $userFeed = UserFeedRunner::getInstance();
            $userFeed->runFeed();
        }

        if (in_array(Feed::FEED_TYPE_ORDER, $feedTypes)) {
            $orderFeed = OrderFeedRunner::getInstance();
            $orderFeed->runFeed();
        }
    }

    /**
     * Get period (seconds)
     *
     * @return integer
     */
    protected function getPeriod()
    {
        return \XLite\Core\Task\Base\Periodic::INT_1_MIN;
    }
}
