<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\Core\Feeds\Order;

use XLite\Base\Singleton;
use PureClarity\Api\Feed\Type\Order as OrderFeed;
use XLite\Module\PureClarity\Personalisation\Core\Feeds\Order\Data\Row;
use XLite\Module\PureClarity\Personalisation\Core\Feeds\Order\Data\Feed;
use XLite\Module\PureClarity\Personalisation\Core\Feeds\Runner as FeedRunner;
use XLite\Module\PureClarity\Personalisation\Core\PureClarity;
use PureClarity\Api\Feed\Feed as PureClarityFeed;

/**
 * class Runner
 *
 * PureClarity Order Feed Runner class
 */
class Runner extends Singleton
{
    /**
     * Runs the PureClarity Order Feed
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
        $productFeed = new OrderFeed($accessKey, $secretKey, $region);

        $feedRunner->runFeed(
            PureClarityFeed::FEED_TYPE_ORDER,
            $feedDataHandler,
            $rowDataHandler,
            $productFeed
        );
    }
}
