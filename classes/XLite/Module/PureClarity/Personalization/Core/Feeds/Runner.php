<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalization\Core\Feeds;

use Exception;
use PureClarity\Api\Feed\Feed;
use XLite\Base\Singleton;
use XLite\Module\PureClarity\Personalization\Core\PureClarity;
use XLite\Module\PureClarity\Personalization\Core\State;

/**
 * class Runner
 *
 * PureClarity Product Feed Runner class
 */
class Runner extends Singleton
{
    /** @var State */
    protected $state;

    /** @var PureClarity */
    protected $pc;

    /** @var int */
    protected $progressChunk;

    /** @var int */
    protected $nextProgress;

    /** @var int */
    protected $totalRows;

    /**
     * Runs a standard PureClarity feed based on the given classes
     *
     * @param string $feedType - the feed type to run (see \PureClarity\Api\Feed\Feed for types)
     * @param FeedDataInterface $feedDataClass - The class to use to get the overall feed data
     * @param FeedRowDataInterface $rowDataClass - the class to use to get the individual row data
     * @param Feed $feedClass - the relevant PureClarity SDK class
     */
    public function runFeed(
        string $feedType,
        FeedDataInterface $feedDataClass,
        FeedRowDataInterface $rowDataClass,
        Feed $feedClass
    ) : void {
        try {
            $pc = $this->getPureClarityClass();
            if ($pc->isActive()) {
                $this->flagFeedStarted($feedType);
                $data = $feedDataClass->getFeedData();

                $this->totalRows = count($data);
                if ($this->totalRows > 0) {
                    $currentRow = 0;
                    $this->progressChunk = round($this->totalRows / 10, 2);
                    $this->nextProgress = $this->progressChunk;

                    $feedClass->start();

                    foreach ($data as $row) {
                        $data = $rowDataClass->getRowData($row);
                        if (!empty($data)) {
                            $feedClass->append($data);
                        }

                        $currentRow++;
                        $this->flagProgress($feedType, $currentRow);
                    }

                    $feedClass->end();
                }

                $this->flagFeedEnd($feedType);
            }
        } catch (Exception $e) {
            $this->flagFeedError($feedType, $e->getMessage());
        }
    }

    /**
     * Updates the progress of the given feed
     *
     * Should only update at most every 10%
     *
     * @param string $feedType
     * @param int $currentRow
     */
    protected function flagProgress(string $feedType, int $currentRow) : void
    {
        if ($currentRow >= $this->nextProgress) {
            $totalProgress = round(($currentRow / $this->totalRows) * 100, 2);
            $state = $this->getStateClass();
            $state->setStateValue($feedType . '_feed_progress', $totalProgress);
            $this->nextProgress += $this->progressChunk;
        }
    }

    /**
     * Sets the given feed as started
     *
     * @param string $feedType
     */
    protected function flagFeedStarted(string $feedType) : void
    {
        $state = $this->getStateClass();
        $state->setStateValue('running_feed', $feedType);
        $state->setStateValue($feedType. '_feed_progress', 0);
    }

    /**
     * Sets an error message in the relevant state fields for the given feed
     *
     * @param string $feedType
     * @param string $message
     */
    protected function flagFeedError(string $feedType, string $message)
    {
        $state = $this->getStateClass();
        $state->setStateValue('running_feed', '');
        $state->setStateValue($feedType. '_feed_progress', 0);
        $state->setStateValue($feedType . '_feed_error', $message);
    }

    /**
     * Sets the relevant state rows for the given feed so that it's classed as finished
     *
     * @param string $feedType
     */
    protected function flagFeedEnd(string $feedType) : void
    {
        $state = $this->getStateClass();
        $state->setStateValue($feedType. '_feed_progress', 0);
        $state->setStateValue('running_feed', '');
        $state->setStateValue($feedType . '_feed_last_run', time());
        $state->setStateValue($feedType . '_feed_error', '');
    }

    /**
     * Gets the PureClarity base class
     *
     * @return PureClarity
     */
    protected function getPureClarityClass() : PureClarity
    {
        if ($this->pc === null) {
            $this->pc = PureClarity::getInstance();
        }

        return $this->pc;
    }

    /**
     * Gets the PureClarity State class
     *
     * @return State
     */
    protected function getStateClass() : State
    {
        if ($this->state === null) {
            $this->state = State::getInstance();
        }

        return $this->state;
    }
}
