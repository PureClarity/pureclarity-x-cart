<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\View\Form\Admin\Dashboard;

class Account extends \XLite\View\Form\AForm
{
    protected function getDefaultTarget()
    {
        return 'pureclarity_dashboard';
    }

    protected function getDefaultAction()
    {
        return 'link_account';
    }
}
