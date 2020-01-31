<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE for license details.
 */

namespace XLite\Module\PureClarity\Personalization\Core\Feeds\User;

use PureClarity\Api\Feed\Feed as PureClarityFeed;
use PureClarity\Api\Feed\Type\User as UserFeed;
use XLite\Base\Singleton;
use XLite\Module\PureClarity\Personalization\Core\Feeds\User\Data\Feed;
use XLite\Module\PureClarity\Personalization\Core\Feeds\User\Data\Row;
use XLite\Module\PureClarity\Personalization\Core\PureClarity;
use XLite\Module\PureClarity\Personalization\Core\Feeds\Runner as FeedRunner;

/**
 * class Runner
 *
 * PureClarity User Feed Runner class
 */
class Runner extends Singleton
{
    /**
     * Runs the User feed
     */
    public function runFeed() : void
    {
        $feedRunner = FeedRunner::getInstance();
        $feedDataHandler = Feed::getInstance();
        $rowDataHandler = Row::getInstance();

        $pc = PureClarity::getInstance();
        $accessKey = $pc->getConfig(PureClarity::CONFIG_ACCESS_KEY);
        $secretKey = $pc->getConfig(PureClarity::CONFIG_SECRET_KEY);
        $region = $pc->getConfig(PureClarity::CONFIG_REGION);
        $feed = new UserFeed($accessKey, $secretKey, $region);

        $feedRunner->runFeed(
            PureClarityFeed::FEED_TYPE_USER,
            $feedDataHandler,
            $rowDataHandler,
            $feed
        );
    }
}
