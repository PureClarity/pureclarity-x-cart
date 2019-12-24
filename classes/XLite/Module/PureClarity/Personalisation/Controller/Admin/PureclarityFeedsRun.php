<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\Controller\Admin;

use XLite\Controller\Admin\AAdmin;
use XLite\Module\PureClarity\Personalisation\Core\Feeds\Category\Runner as CategoryFeedRunner;
use XLite\Module\PureClarity\Personalisation\Core\Feeds\Product\Runner as ProductFeedRunner;
use XLite\Module\PureClarity\Personalisation\Core\Feeds\Brand\Runner as BrandFeedRunner;
use XLite\Module\PureClarity\Personalisation\Core\Feeds\User\Runner as UserFeedRunner;
use XLite\Module\PureClarity\Personalisation\Core\Feeds\Order\Runner as OrderFeedRunner;
use XLite\Module\PureClarity\Personalisation\Core\Delta\Product as ProductDelta;

/**
 * PureClarity Dashboard Page
 */
class PureclarityFeedsRun extends AAdmin
{
    /**
     * Does a signup request
     */
    public function doNoAction()
    {
        ProductFeedRunner::getInstance()->runFeed();
        die();
    }
}
