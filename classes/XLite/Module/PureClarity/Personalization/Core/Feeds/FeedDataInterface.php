<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE for license details.
 */

namespace XLite\Module\PureClarity\Personalization\Core\Feeds;

interface FeedDataInterface
{
    /**
     * Should return a count of the data to be used in the feed generation process
     *
     * @return int
     */
    public function getFeedCount() : int;

    /**
     * Should return an array of data to be used in the feed generation process
     *
     * @return array[]
     */
    public function getFeedData(int $page, int $pageSize) : array;

    /**
     * Does any memory management needed after a page of data is processed
     *
     * @return void
     */
    public function cleanPage() : void;
}
