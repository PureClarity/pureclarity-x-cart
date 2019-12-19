<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\View\Form\Admin\Dashboard;

class Feeds extends \XLite\View\Form\AForm
{
    protected function getDefaultTarget()
    {
        return 'pureclarity_feeds';
    }

    protected function getDefaultAction()
    {
        return 'request';
    }

    protected function getClassName()
    {
        return parent::getClassName() . ' pureclarity-feeds';
    }
}
