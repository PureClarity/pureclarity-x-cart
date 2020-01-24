<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalization\Controller\Admin;

use PureClarity\Api\Feed\Feed;
use XLite\Controller\Admin\AAdmin;
use XLite\Module\PureClarity\Personalization\Core\Feeds\Status;

/**
 * Class PureclarityFeedProgress
 *
 * PureClarity Feed Progress AJAX Page Controller
 */
class PureclarityFeedProgress extends AAdmin
{
    /**
     * Default AJAX action
     *
     * Checks the current status of all feeds and outputs as json string used by JS to update the display
     */
    public function doNoAction() : void
    {
        $feedStatus = Status::getInstance();

        $status = [
            Feed::FEED_TYPE_PRODUCT => $feedStatus->getFeedStatus(Feed::FEED_TYPE_PRODUCT),
            Feed::FEED_TYPE_CATEGORY => $feedStatus->getFeedStatus(Feed::FEED_TYPE_CATEGORY),
            Feed::FEED_TYPE_USER => $feedStatus->getFeedStatus(Feed::FEED_TYPE_USER),
            Feed::FEED_TYPE_BRAND => $feedStatus->getFeedStatus(Feed::FEED_TYPE_BRAND),
            Feed::FEED_TYPE_ORDER => $feedStatus->getFeedStatus(Feed::FEED_TYPE_ORDER),
            'in_progress' => $feedStatus->getAreFeedsInProgress([
                Feed::FEED_TYPE_PRODUCT,
                Feed::FEED_TYPE_CATEGORY,
                Feed::FEED_TYPE_USER,
                Feed::FEED_TYPE_BRAND,
                Feed::FEED_TYPE_ORDER
            ])
        ];


        $return = json_encode($status);

        header('Content-Type: application/json; charset=UTF-8');
        header('Content-Length: ' . strlen($return));
        header('ETag: ' . md5($return));

        print ($return);
    }
}
