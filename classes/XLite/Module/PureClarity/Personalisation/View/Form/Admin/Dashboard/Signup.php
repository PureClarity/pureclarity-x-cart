<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\View\Form\Admin\Dashboard;

class Signup extends \XLite\View\Form\AForm
{
    protected function getDefaultTarget()
    {
        return 'pureclarity_dashboard';
    }

    protected function getDefaultAction()
    {
        return 'signup';
    }

    protected function getClassName()
    {
        return parent::getClassName() . ' pureclarity-signup';
    }
}
