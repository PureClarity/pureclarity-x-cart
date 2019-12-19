<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\Controller\Admin;

use XLite\Controller\Admin\AAdmin;
use XLite\Core\Request;
use XLite\Core\TopMessage;
use XLite\Module\PureClarity\Personalisation\Core\State;

/**
 * PureClarity Dashboard Page
 */
class PureclarityFeeds extends AAdmin
{
    /**
     * Return the current page title
     *
     * @return string
     */
    public function getTitle()
    {
        return static::t('Send PureClarity Feeds');
    }

    /**
     * Does a signup request
     */
    public function doActionRequest()
    {
        $request = Request::getInstance();

        $feedTypes = [];

        if ($request->product) {
            $feedTypes[] = 'product';
        }

        if ($request->category) {
            $feedTypes[] = 'category';
        }

        if ($request->user) {
            $feedTypes[] = 'user';
        }

        if ($request->brand) {
            $feedTypes[] = 'brand';
        }

        if ($request->order) {
            $feedTypes[] = 'order';
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

        $this->setReturnURL(
            $this->buildURL('pureclarity_dashboard', '', array())
        );
    }
}
