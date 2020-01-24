<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalization\Controller\Admin;

use PureClarity\Api\Feed\Feed;
use XLite\Controller\Admin\AAdmin;
use XLite\Core\Request;
use XLite\Core\TopMessage;
use XLite\Module\PureClarity\Personalization\Core\State;

/**
 * Class PureclarityFeeds
 *
 * PureClarity Feed Request ajax Page Controller
 */
class PureclarityFeeds extends AAdmin
{
    /**
     * Return the current page title
     *
     * @return string
     */
    public function getTitle() : string
    {
        return static::t('Send PureClarity Feeds');
    }

    /**
     * "request" action
     *
     * Takes the post from the manual feed run form and
     * saves the request to the pureclarity_state table to be picked up by the cron task
     */
    public function doActionRequest() : void
    {
        $request = Request::getInstance();

        $feedTypes = [];

        if ($request->product) {
            $feedTypes[] = Feed::FEED_TYPE_PRODUCT;
        }

        if ($request->category) {
            $feedTypes[] = Feed::FEED_TYPE_CATEGORY;
        }

        if ($request->user) {
            $feedTypes[] = Feed::FEED_TYPE_USER;
        }

        if ($request->brand) {
            $feedTypes[] = Feed::FEED_TYPE_BRAND;
        }

        if ($request->order) {
            $feedTypes[] = Feed::FEED_TYPE_ORDER;
        }

        if (empty($feedTypes)) {
            TopMessage::getInstance()->add(
                static::t('Please choose one or more feeds to send to PureClarity'),
                [],
                null,
                TopMessage::ERROR
            );
        }

        $state = State::getInstance();
        $state->setStateValue('requested_feeds', json_encode($feedTypes));
        foreach ($feedTypes as $feed) {
            $state->setStateValue($feed . '_feed_error', '');
        }


        $this->setReturnURL(
            $this->buildURL('pureclarity_dashboard', '', array())
        );
    }
}
