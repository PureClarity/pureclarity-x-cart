<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\Core\Feeds\Category;

use XLite\Base\Singleton;
use PureClarity\Api\Feed\Type\Category as CategoryFeed;
use XLite\Module\PureClarity\Personalisation\Core\Feeds\Category\Data\Row;
use XLite\Module\PureClarity\Personalisation\Core\Feeds\Category\Data\Feed;
use XLite\Module\PureClarity\Personalisation\Core\Feeds\Runner as FeedRunner;
use XLite\Module\PureClarity\Personalisation\Core\Pureclarity;
use PureClarity\Api\Feed\Feed as PureClarityFeed;

/**
 * class Runner
 *
 * PureClarity Category Feed Runner class
 */
class Runner extends Singleton
{
    /**
     * Runs the PureClarity Category Feed
     */
    public function runFeed()
    {
        $feedRunner = FeedRunner::getInstance();
        $feedDataHandler = Feed::getInstance();
        $rowDataHandler = Row::getInstance();

        $pc = Pureclarity::getInstance();
        $accessKey = $pc->getConfig(Pureclarity::CONFIG_ACCESS_KEY);
        $secretKey = $pc->getConfig(Pureclarity::CONFIG_SECRET_KEY);
        $region = $pc->getConfig(Pureclarity::CONFIG_REGION);
        $categoryFeed = new CategoryFeed($accessKey, $secretKey, $region);

        $feedRunner->runFeed(
            PureClarityFeed::FEED_TYPE_PRODUCT,
            $feedDataHandler,
            $rowDataHandler,
            $categoryFeed
        );
    }
}
