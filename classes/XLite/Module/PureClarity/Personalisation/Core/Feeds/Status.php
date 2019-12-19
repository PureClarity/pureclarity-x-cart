<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\Core\Feeds;

use XLite\Base\Singleton;
use XLite\Module\PureClarity\Personalisation\Core\State;
use XLite\Core\Config;

/**
 * Class FeedStatus
 *
 * Feed status checker model
 */
class Status extends Singleton
{
    /** @var mixed[] $feedStatusData */
    private $feedStatusData;

    /** @var array[] $feedErrors */
    private $feedErrors;

    /** @var mixed[] $progressData */
    private $progressData;

    /** @var mixed[] $requestedFeedData */
    private $requestedFeedData;

    /**
     * Returns whether any of the feed types provided are currently in progress
     *
     * @param string[] $types
     *
     * @return bool
     */
    public function getAreFeedsInProgress($types)
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
     * Returns whether all of the feed types provided are currently disabled
     *
     * @param string[] $types
     *
     * @return bool
     */
    public function getAreFeedsDisabled($types)
    {
        $disabled = true;
        foreach ($types as $type) {
            $status = $this->getFeedStatus($type);
            if ($status['enabled'] === true) {
                $disabled = false;
                break;
            }
        }

        return $disabled;
    }

    /**
     * Returns the status of the product feed
     *
     * @param string $type
     * @return mixed[]
     */
    public function getFeedStatus($type)
    {
        if (!isset($this->feedStatusData[$type])) {
            $config = Config::getInstance();
            $enabled = $config->PureClarity->Personalisation->pc_enabled;
            $accessKey = $config->PureClarity->Personalisation->pc_access_key;
            $secretKey = $config->PureClarity->Personalisation->pc_secret_key;
            $region = $config->PureClarity->Personalisation->pc_region;
            $sendBrandFeed = $config->PureClarity->Personalisation->pc_feeds_brands;

            $status = [
                'enabled' => true,
                'error' => false,
                'running' => false,
                'class' => 'pc-feed-not-sent',
                'label' => static::t('Not Sent')
            ];

            if (empty($enabled) || empty($accessKey) || empty($secretKey) || empty($region)) {
                $status['enabled'] = false;
                $status['label'] = static::t('Not Enabled');
                $status['class'] = 'pc-feed-disabled';
            }

            if ($type === 'brand' &&
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
    private function hasFeedBeenRequested($feedType)
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
    private function getFeedError($feedType)
    {
        $state = State::getInstance();
        return $state->getStateValue($feedType . '_feed_error');
    }

    /**
     * Checks for the running_feeds state row and returns whether the given feed type is in it's data
     *
     * @param string $feedType
     * @return bool
     */
    private function isFeedWaiting($feedType)
    {
        $state = State::getInstance();
        $running = $state->getStateValue('running_feed');
        return !empty($running) && $running !== $feedType;
    }

    /**
     * Gets progress data from the state table
     *
     * @param string $feedType
     * @return bool|float
     */
    private function feedProgress($feedType)
    {
        if (!isset($this->progressData[$feedType])) {
            $this->progressData[$feedType] = '0';
            $state = State::getInstance();
            $this->progressData[$feedType] = $state->getStateValue($feedType . '_feed_progress');
        }

        return $this->progressData[$feedType];
    }

    /**
     * Gets schedule data from the state table
     *
     * @return string[]
     */
    private function getScheduledFeedData()
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
