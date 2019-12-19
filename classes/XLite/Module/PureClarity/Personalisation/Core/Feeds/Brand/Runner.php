<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\Core\Feeds\Brand;

use XLite\Base\Singleton;
use PureClarity\Api\Feed\Type\Brand as BrandFeed;
use XLite\Module\PureClarity\Personalisation\Core\Feeds\Brand\Data\Row;
use XLite\Module\PureClarity\Personalisation\Core\Feeds\Brand\Data\Feed;
use XLite\Module\PureClarity\Personalisation\Core\Feeds\Runner as FeedRunner;
use XLite\Module\PureClarity\Personalisation\Core\Pureclarity;
use PureClarity\Api\Feed\Feed as PureClarityFeed;

/**
 * class Runner
 *
 * PureClarity Brand Feed Runner class
 */
class Runner extends Singleton
{
    /**
     * Runs the PureClarity Brands Feed
     */
    public function runFeed()
    {
        $pc = Pureclarity::getInstance();
        $feedEnabled = $pc->getConfig(Pureclarity::CONFIG_FEEDS_BRAND);
        $parent = $pc->getConfig(Pureclarity::CONFIG_FEEDS_BRAND_PARENT);
        if (empty($feedEnabled) || empty($parent)) {
            return;
        }

        $feedRunner = FeedRunner::getInstance();
        $feedDataHandler = Feed::getInstance();
        $rowDataHandler = Row::getInstance();

        $pc = Pureclarity::getInstance();
        $accessKey = $pc->getConfig(Pureclarity::CONFIG_ACCESS_KEY);
        $secretKey = $pc->getConfig(Pureclarity::CONFIG_SECRET_KEY);
        $region = $pc->getConfig(Pureclarity::CONFIG_REGION);
        $feed = new BrandFeed($accessKey, $secretKey, $region);

        $feedRunner->runFeed(
            PureClarityFeed::FEED_TYPE_PRODUCT,
            $feedDataHandler,
            $rowDataHandler,
            $feed
        );
    }
}
