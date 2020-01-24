<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalization\Core\Task;

use XLite\Core\Task\Base\Periodic;
use XLite\Module\PureClarity\Personalization\Core\Delta\Product as ProductDelta;

/**
 * Scheduled task that checks for & sends deltas
 */
class Delta extends Periodic
{
    /**
     * Returns the title of this task
     *
     * @return string
     */
    public function getTitle() : string
    {
        return static::t('PureClarity Deltas');
    }

    /**
     * Run the PureClarity Product Delta
     */
    protected function runStep() : void
    {
        ProductDelta::getInstance()->runDelta();
    }

    /**
     * Get recurrence period (seconds)
     *
     * @return int
     */
    protected function getPeriod() : int
    {
        return Periodic::INT_1_MIN;
    }
}
