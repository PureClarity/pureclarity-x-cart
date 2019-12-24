<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\Core\Feeds;

interface FeedDataInterface
{
    /**
     * Should return an array of objects to be used in the feed generation process
     *
     * @return object[]
     */
    public function getFeedData() : array;
}
