<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE for license details.
 */

namespace XLite\Module\PureClarity\Personalization\Core\Feeds\Product;

use PureClarity\Api\Feed\Feed as PureClarityFeed;
use PureClarity\Api\Feed\Type\Product as ProductFeed;
use XLite\Base\Singleton;
use XLite\Module\PureClarity\Personalization\Core\Feeds\Product\Data\Feed;
use XLite\Module\PureClarity\Personalization\Core\Feeds\Product\Data\Row;
use XLite\Module\PureClarity\Personalization\Core\PureClarity;
use XLite\Module\PureClarity\Personalization\Core\Feeds\Runner as FeedRunner;

/**
 * class Runner
 *
 * PureClarity Product Feed Runner class
 */
class Runner extends Singleton
{
    /**
     * Runs the PureClarity Product Feed
     */
    public function runFeed()
    {
        $feedRunner = FeedRunner::getInstance();
        $feedDataHandler = Feed::getInstance();
        $rowDataHandler = Row::getInstance();

        $pc = PureClarity::getInstance();
        $accessKey = $pc->getConfig(PureClarity::CONFIG_ACCESS_KEY);
        $secretKey = $pc->getConfig(PureClarity::CONFIG_SECRET_KEY);
        $region = $pc->getConfig(PureClarity::CONFIG_REGION);
        $productFeed = new ProductFeed($accessKey, $secretKey, $region);

        $feedRunner->runFeed(
            PureClarityFeed::FEED_TYPE_PRODUCT,
            $feedDataHandler,
            $rowDataHandler,
            $productFeed
        );
    }
}
