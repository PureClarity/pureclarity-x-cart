<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\Core\Feeds\User;

use PureClarity\Api\Feed\Feed as PureClarityFeed;
use PureClarity\Api\Feed\Type\User as UserFeed;
use XLite\Base\Singleton;
use XLite\Module\PureClarity\Personalisation\Core\Feeds\User\Data\Feed;
use XLite\Module\PureClarity\Personalisation\Core\Feeds\User\Data\Row;
use XLite\Module\PureClarity\Personalisation\Core\Pureclarity;
use XLite\Module\PureClarity\Personalisation\Core\Feeds\Runner as FeedRunner;

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
    public function runFeed()
    {
        $feedRunner = FeedRunner::getInstance();
        $feedDataHandler = Feed::getInstance();
        $rowDataHandler = Row::getInstance();

        $pc = Pureclarity::getInstance();
        $accessKey = $pc->getConfig(Pureclarity::CONFIG_ACCESS_KEY);
        $secretKey = $pc->getConfig(Pureclarity::CONFIG_SECRET_KEY);
        $region = $pc->getConfig(Pureclarity::CONFIG_REGION);
        $feed = new UserFeed($accessKey, $secretKey, $region);

        $feedRunner->runFeed(
            PureClarityFeed::FEED_TYPE_PRODUCT,
            $feedDataHandler,
            $rowDataHandler,
            $feed
        );
    }
}
