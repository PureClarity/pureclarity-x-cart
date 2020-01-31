<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE for license details.
 */

namespace XLite\Module\PureClarity\Personalization\Core\Feeds;

use PureClarity\Api\Feed\Feed;
use XLite\Base\Singleton;
use XLite\Module\PureClarity\Personalization\Core\PureClarity;
use XLite\Module\PureClarity\Personalization\Core\State;

/**
 * Class FeedStatus
 *
 * Feed status checker model
 */
class Status extends Singleton
{
    /** @var mixed[] $feedStatusData */
    protected $feedStatusData;

    /** @var string[] $feedErrors */
    protected $feedErrors;

    /** @var mixed[] $progressData */
    protected $progressData;

    /** @var mixed[] $requestedFeedData */
    protected $requestedFeedData;

    /**
     * Returns whether any of the feed types provided are currently in progress
     *
     * @param string[] $types
     *
     * @return bool
     */
    public function getAreFeedsInProgress(array $types) : bool
    {
        $inProgress = false;
        foreach ($types as $type) {
            $status = $this->getFeedStatus($type);
            if ($status['running'] === true) {
                $inProgress = true;
            }
        }

        return $inProgress;
    }

    /**
     * Returns the status of the product feed
     *
     * @param string $type
     * @return mixed[]
     */
    public function getFeedStatus(string $type) : array
    {
        if (!isset($this->feedStatusData[$type])) {
            $pc = PureClarity::getInstance();

            $status = [
                'enabled' => true,
                'error' => false,
                'running' => false,
                'class' => 'pc-feed-not-sent',
                'label' => static::t('Not Sent')
            ];

            if ($pc->isActive() === false) {
                $status['enabled'] = false;
                $status['label'] = static::t('Not Enabled');
                $status['class'] = 'pc-feed-disabled';
            }

            $sendBrandFeed = $pc->getConfigFlag(PureClarity::CONFIG_FEEDS_BRAND);

            if ($type === Feed::FEED_TYPE_BRAND &&
                $status['enabled'] === true &&
                empty($sendBrandFeed)
            ) {
                $status['enabled'] = false;
                $status['label'] = static::t('Not Enabled');
                $status['class'] = 'pc-feed-disabled';
            }

            if ($status['enabled'] === false) {
                $this->feedStatusData[$type] = $status;
                return $this->feedStatusData[$type];
            }

            if ($this->getFeedError($type) === true) {
                $status['error'] = true;
                $status['label'] = static::t('Error, please see logs for more information');
                $status['class'] = 'pc-feed-error';
                $this->feedStatusData[$type] = $status;
                return $this->feedStatusData[$type];
            }

            // check if it's been requested
            $requested = $this->hasFeedBeenRequested($type);

            if ($requested) {
                $status['running'] = true;
                $status['label'] = static::t('Waiting for feed run to start');
                $status['class'] = 'pc-feed-waiting';
            }

            // check if it's been requested
            $requested = $this->isFeedWaiting($type);
            if ($requested) {
                $status['running'] = true;
                $status['label'] = static::t('Waiting for other feeds to finish');
                $status['class'] = 'pc-feed-waiting';
            }

            if ($status['running']) {
                // check if it's in progress
                $progress = $this->feedProgress($type);
                if (!empty($progress)) {
                    $status['running'] = true;
                    $status['label'] = static::t(
                        'In progress: {{progress}}%',
                        [
                            'progress' => $progress
                        ]
                    );
                    $status['class'] = 'pc-feed-in-progress';
                }
            }

            if ($status['running'] !== true) {
                // check it's last run date
                $state = State::getInstance();
                $lastProductFeedDate = $state->getStateValue($type . '_feed_last_run');

                if ($lastProductFeedDate) {
                    $status['label'] = static::t(
                        'Last sent {{time}}',
                        [
                            'time' => date('Y-m-d H:i:s', $lastProductFeedDate)
                        ]
                    );
                    $status['class'] = 'pc-feed-complete';
                }
            }

            $this->feedStatusData[$type] = $status;
        }

        return $this->feedStatusData[$type];
    }

    /**
     * Checks for the scheduled feed file and returns whether the given feed type is in it's data
     *
     * @param string $feedType
     * @return bool
     */
    protected function hasFeedBeenRequested(string $feedType) : bool
    {
        $requested = false;
        $scheduleData = $this->getScheduledFeedData();

        if (!empty($scheduleData)) {
            $requested = in_array($feedType, $scheduleData);
        }

        return $requested;
    }

    /**
     * Checks for & returns the <feedType>_feed_error state row
     *
     * @param string $feedType
     * @return bool
     */
    protected function getFeedError(string $feedType) : bool
    {
        if ($this->feedErrors === null || !isset($this->feedErrors[$feedType])) {
            $state = State::getInstance();
            $this->feedErrors[$feedType] = $state->getStateValue($feedType . '_feed_error');
        }

        return $this->feedErrors[$feedType];
    }

    /**
     * Checks for the running_feeds state row and returns whether the given feed type is in it's data
     *
     * @param string $feedType
     * @return bool
     */
    protected function isFeedWaiting(string $feedType) : bool
    {
        $state = State::getInstance();
        $running = $state->getStateValue('running_feed');
        return !empty($running) && $running !== $feedType;
    }

    /**
     * Gets progress data from the state table
     *
     * @param string $feedType
     * @return string
     */
    protected function feedProgress(string $feedType) : string
    {
        if (!isset($this->progressData[$feedType])) {
            $state = State::getInstance();
            $progress = $state->getStateValue($feedType . '_feed_progress');
            $this->progressData[$feedType] = empty($progress) ? '0' : $progress;
        }

        return $this->progressData[$feedType];
    }

    /**
     * Gets schedule data from the state table
     *
     * @return string[]
     */
    protected function getScheduledFeedData() : array
    {
        if ($this->requestedFeedData === null) {
            $state = State::getInstance();
            $requestedFeeds = $state->getStateValue('requested_feeds');

            if (!empty($requestedFeeds)) {
                $this->requestedFeedData = json_decode($requestedFeeds);
            } else {
                $this->requestedFeedData = [];
            }
        }

        return $this->requestedFeedData;
    }
}
