<?php

/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE for license details.
 */

namespace XLite\Module\PureClarity\Personalization\View\Customer;

use XLite\View\AView;

/**
 * PureClarity Resources class
 * This widget embeds PureClarity  & default CSS
 *
 * @ListChild (list="head", zone="customer", weight="101")
 */
class Resources extends AView
{
    /**
     * Gets the current list of JS for the overall View and adds the js for this view to it
     *
     * @return array
     */
    public function getJSFiles(): array
    {
        $list = parent::getJSFiles();
        $list[] = $this->getDir() . '/js/events.js';
        return $list;
    }

    /**
     * Gets the current list of CSS for the overall View and adds the js for this view to it
     *
     * @return array
     */
    public function getCSSFiles(): array
    {
        $list = parent::getCSSFiles();
        $list[] = $this->getDir() . '/css/pc.css';
        return $list;
    }

    /**
     * Return templates directory name
     *
     * @return string
     */
    protected function getDir(): string
    {
        return 'modules/PureClarity/Personalization/';
    }

    /**
     * Return widget template
     *
     * @return string
     */
    protected function getDefaultTemplate() : string
    {
        return '';
    }
}
