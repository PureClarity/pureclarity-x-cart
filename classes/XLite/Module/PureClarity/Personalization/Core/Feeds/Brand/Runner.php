<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE for license details.
 */

namespace XLite\Module\PureClarity\Personalization\Core\Feeds\Brand;

use XLite\Base\Singleton;
use PureClarity\Api\Feed\Type\Brand as BrandFeed;
use XLite\Module\PureClarity\Personalization\Core\Feeds\Brand\Data\Row;
use XLite\Module\PureClarity\Personalization\Core\Feeds\Brand\Data\Feed;
use XLite\Module\PureClarity\Personalization\Core\Feeds\Runner as FeedRunner;
use XLite\Module\PureClarity\Personalization\Core\PureClarity;
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
        $pc = PureClarity::getInstance();
        $feedEnabled = $pc->getConfigFlag(PureClarity::CONFIG_FEEDS_BRAND);
        $parent = $pc->getConfig(PureClarity::CONFIG_FEEDS_BRAND_PARENT);
        if ($feedEnabled === false || empty($parent)) {
            return;
        }

        $feedRunner = FeedRunner::getInstance();
        $feedDataHandler = Feed::getInstance();
        $rowDataHandler = Row::getInstance();

        $pc = PureClarity::getInstance();
        $accessKey = $pc->getConfig(PureClarity::CONFIG_ACCESS_KEY);
        $secretKey = $pc->getConfig(PureClarity::CONFIG_SECRET_KEY);
        $region = $pc->getConfig(PureClarity::CONFIG_REGION);
        $feed = new BrandFeed($accessKey, $secretKey, $region);

        $feedRunner->runFeed(
            PureClarityFeed::FEED_TYPE_BRAND,
            $feedDataHandler,
            $rowDataHandler,
            $feed
        );
    }
}
