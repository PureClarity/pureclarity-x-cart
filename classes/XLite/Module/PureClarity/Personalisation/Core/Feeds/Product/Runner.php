<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\Core\Feeds\Product;

use PureClarity\Api\Feed\Feed as PureClarityFeed;
use PureClarity\Api\Feed\Type\Product as ProductFeed;
use XLite\Base\Singleton;
use XLite\Module\PureClarity\Personalisation\Core\Feeds\Product\Data\Feed;
use XLite\Module\PureClarity\Personalisation\Core\Feeds\Product\Data\Row;
use XLite\Module\PureClarity\Personalisation\Core\Pureclarity;
use XLite\Module\PureClarity\Personalisation\Core\Feeds\Runner as FeedRunner;

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

        $pc = Pureclarity::getInstance();
        $accessKey = $pc->getConfig(Pureclarity::CONFIG_ACCESS_KEY);
        $secretKey = $pc->getConfig(Pureclarity::CONFIG_SECRET_KEY);
        $region = $pc->getConfig(Pureclarity::CONFIG_REGION);
        $productFeed = new ProductFeed($accessKey, $secretKey, $region);

        $feedRunner->runFeed(
            PureClarityFeed::FEED_TYPE_PRODUCT,
            $feedDataHandler,
            $rowDataHandler,
            $productFeed
        );
    }
}
