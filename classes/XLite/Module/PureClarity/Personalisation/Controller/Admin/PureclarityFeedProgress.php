<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\Controller\Admin;

use PureClarity\Api\Feed\Feed;
use XLite\Controller\Admin\AAdmin;
use XLite\Module\PureClarity\Personalisation\Core\Feeds\Status;

/**
 * PureClarity Dashboard Page
 */
class PureclarityFeedProgress extends AAdmin
{
    public function doNoAction()
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
