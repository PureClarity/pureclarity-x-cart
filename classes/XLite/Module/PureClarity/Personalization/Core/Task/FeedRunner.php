<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE for license details.
 */

namespace XLite\Module\PureClarity\Personalization\Core\Task;

use PureClarity\Api\Feed\Feed;
use XLite\Core\Task\Base\Periodic;
use XLite\Module\PureClarity\Personalization\Core\Feeds\Category\Runner as CategoryFeedRunner;
use XLite\Module\PureClarity\Personalization\Core\Feeds\Product\Runner as ProductFeedRunner;
use XLite\Module\PureClarity\Personalization\Core\Feeds\Brand\Runner as BrandFeedRunner;
use XLite\Module\PureClarity\Personalization\Core\Feeds\User\Runner as UserFeedRunner;
use XLite\Module\PureClarity\Personalization\Core\Feeds\Order\Runner as OrderFeedRunner;
use XLite\Module\PureClarity\Personalization\Core\PureClarity;
use XLite\Module\PureClarity\Personalization\Core\State;

/**
 * Scheduled task that checks for & sends feeds
 */
class FeedRunner extends Periodic
{
    /** @var string - hour that the feed is due to run on, in date('H') format. */
    public const FEED_HOUR = '03';

    /**
     * Returns the title of this task
     *
     * @return string
     */
    public function getTitle() : string
    {
        return static::t('PureClarity Feeds');
    }

    /**
     * Runs any requested feeds
     */
    protected function runStep() : void
    {
        $pc = PureClarity::getInstance();
        if ($pc->isActive()) {
            $this->markCronAsRun();
            $isConfigured = State::getInstance()->getStateValue('is_configured');
            if ($isConfigured === '1') {
                $this->checkRequestedFeeds();
                $this->checkNightlyFeed();
            }
        }
    }

    /**
     * Checks the has_cron_run PureClarity state, and sends it to 1 if it's not that already
     *
     * If has_cron_run not set to 1, then a warning appears on the dashboard
     */
    protected function markCronAsRun() : void
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
    protected function checkRequestedFeeds() : void
    {
        $state = State::getInstance();
        // Run scheduled Feeds
        $feeds = $state->getStateValue('requested_feeds');
        $running = $state->getStateValue('requested_feeds_running');
        if (!empty($feeds) && $running !== '1') {
            $state->setStateValue('requested_feeds_running', '1');
            $feedTypes = json_decode($feeds);
            $this->sendFeeds($feedTypes);
            $state->setStateValue('requested_feeds', '');
            $state->setStateValue('requested_feeds_running', '');
        }
    }

    /**
     * Checks to see if it's the right time to run the nightly feed, and if so, runs all relevant feeds
     */
    protected function checkNightlyFeed() : void
    {
        $pc = PureClarity::getInstance();
        $nightlyFeeds = $pc->getConfigFlag(PureClarity::CONFIG_FEEDS_NIGHTLY);
        if ($nightlyFeeds) {
            $state = State::getInstance();
            $nightlyFeedRun = $state->getStateValue('nightly_feed_run');
            $hour = date('H');
            if ($hour === self::FEED_HOUR && empty($nightlyFeedRun)) {
                // set the feed run status early, so if this takes long we don't get multiple instances
                $state->setStateValue('nightly_feed_run', '1');
                $this->sendFeeds([
                    Feed::FEED_TYPE_PRODUCT,
                    Feed::FEED_TYPE_CATEGORY,
                    Feed::FEED_TYPE_BRAND,
                    Feed::FEED_TYPE_USER,
                ]);
            } elseif ($hour !== self::FEED_HOUR && $nightlyFeedRun === '1') {
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
    protected function sendFeeds(array $feedTypes)
    {

        if (in_array(Feed::FEED_TYPE_PRODUCT, $feedTypes)) {
            $productFeed = ProductFeedRunner::getInstance();
            $productFeed->runFeed();
            $feedTypes = $this->removeFeedFromState($feedTypes, Feed::FEED_TYPE_PRODUCT);
        }

        if (in_array(Feed::FEED_TYPE_CATEGORY, $feedTypes)) {
            $categoryFeed = CategoryFeedRunner::getInstance();
            $categoryFeed->runFeed();
            $feedTypes = $this->removeFeedFromState($feedTypes, Feed::FEED_TYPE_CATEGORY);
        }

        if (in_array(Feed::FEED_TYPE_BRAND, $feedTypes)) {
            $brandFeed = BrandFeedRunner::getInstance();
            $brandFeed->runFeed();
        }

        if (in_array(Feed::FEED_TYPE_USER, $feedTypes)) {
            $feedTypes = $this->removeFeedFromState($feedTypes, Feed::FEED_TYPE_USER);
            $userFeed = UserFeedRunner::getInstance();
            $userFeed->runFeed();
            $feedTypes = $this->removeFeedFromState($feedTypes, Feed::FEED_TYPE_BRAND);
        }

        if (in_array(Feed::FEED_TYPE_ORDER, $feedTypes)) {
            $orderFeed = OrderFeedRunner::getInstance();
            $orderFeed->runFeed();
            $feedTypes = $this->removeFeedFromState($feedTypes, Feed::FEED_TYPE_ORDER);
        }
    }

    /**
     * Removes the indicated feed from the feed types
     *
     * @param array $feedTypes
     * @param string $feedType
     * @return mixed
     */
    protected function removeFeedFromState(array $feedTypes, string $feedType) : array
    {
        $state = State::getInstance();
        if (($key = array_search($feedType, $feedTypes)) !== false) {
            unset($feedTypes[$key]);
        }
        $state->setStateValue('requested_feeds', json_encode(array_values($feedTypes)));
        return $feedTypes;
    }

    /**
     * Get recurrence period (seconds)
     *
     * @return int
     */
    protected function getPeriod() : int
    {
        return Periodic::INT_1_MIN;
    }
}
