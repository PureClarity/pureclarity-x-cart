<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\Core\Feeds;

use PureClarity\Api\Feed\Feed;
use XLite\Base\Singleton;
use XLite\Module\PureClarity\Personalisation\Core\Pureclarity;
use XLite\Module\PureClarity\Personalisation\Core\State;

/**
 * class Runner
 *
 * PureClarity Product Feed Runner class
 */
class Runner extends Singleton
{
    protected $state;
    protected $pc;
    protected $progressChunk;
    protected $nextProgress;
    protected $totalRows;

    /**
     * @param string $feedType
     * @param FeedDataInterface $feedDataClass
     * @param FeedRowDataInterface $rowDataClass
     * @param Feed $feedClass
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
                    $i = 0;
                    $this->progressChunk = round($this->totalRows / 10, 2);
                    $this->nextProgress = $this->progressChunk;

                    $feedClass->start();

                    foreach ($data as $row) {
                        $data = $rowDataClass->getRowData($row);
                        if (!empty($data)) {
                            $feedClass->append($data);
                        }

                        $i++;
                        $this->flagProgress($feedType, $i);
                    }

                    $feedClass->end();
                }

                $this->flagFeedEnd($feedType);
            }
        } catch (\Exception $e) {
            $this->flagFeedError($feedType, $e->getMessage());
        }
    }

    protected function flagProgress(string $feedType, int $i) : void
    {
        if ($i >= $this->nextProgress) {
            $totalProgress = round(($i / $this->totalRows) * 100, 2);
            $state = $this->getStateClass();
            $state->setStateValue($feedType . '_feed_progress', $totalProgress);
            $this->nextProgress += $this->progressChunk;
        }
    }

    protected function flagFeedStarted(string $feedType) : void
    {
        $state = $this->getStateClass();
        $state->setStateValue('running_feed', $feedType);
        $state->setStateValue($feedType. '_feed_progress', 0);
    }

    protected function flagFeedError(string $feedType, string $message)
    {
        $state = $this->getStateClass();
        $state->setStateValue('running_feed', '');
        $state->setStateValue($feedType. '_feed_progress', 0);
        $state->setStateValue($feedType . '_feed_error', $message);
    }


    protected function flagFeedEnd(string $feedType) : void
    {
        $state = $this->getStateClass();
        $state->setStateValue($feedType. '_feed_progress', 0);
        $state->setStateValue('running_feed', '');
        $state->setStateValue($feedType . '_feed_last_run', time());
        $state->setStateValue($feedType . '_feed_error', '');
    }

    /**
     * @return Pureclarity
     */
    protected function getPureClarityClass() : Pureclarity
    {
        if ($this->pc === null) {
            $this->pc = Pureclarity::getInstance();
        }

        return $this->pc;
    }

    /**
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
