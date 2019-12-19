<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\Core\Task;

use XLite\Core\Database;
use XLite\Module\PureClarity\Personalisation\Core\Delta\Product as ProductDelta;

/**
 * Scheduled task that checks for & sends deltas
 */
class Delta extends \XLite\Core\Task\Base\Periodic
{
    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return static::t('PureClarity Deltas');
    }

    /**
     * Run step
     *
     * @return void
     */
    protected function runStep()
    {
        ProductDelta::getInstance()->runDelta();
    }

    /**
     * Get period (seconds)
     *
     * @return integer
     */
    protected function getPeriod()
    {
        return \XLite\Core\Task\Base\Periodic::INT_1_MIN;
    }
}
