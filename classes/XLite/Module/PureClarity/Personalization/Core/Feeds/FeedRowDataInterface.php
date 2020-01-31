<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE for license details.
 */

namespace XLite\Module\PureClarity\Personalization\Core\Feeds;

interface FeedRowDataInterface
{
    /**
     * Returns an array of data for the feed, derived from the object provided
     *
     * @param object $row
     * @return array
     */
    public function getRowData($row) : array;
}
