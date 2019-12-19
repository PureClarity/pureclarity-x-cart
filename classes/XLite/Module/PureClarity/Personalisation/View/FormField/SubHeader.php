<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\View\FormField;

use XLite\View\FormField\Separator\ASeparator;

/**
 * \XLite\View\FormField\Separator\Regular
 */
class SubHeader extends ASeparator
{
    /**
     * Return field template
     *
     * @return string
     */
    protected function getDir()
    {
        return 'modules/PureClarity/Personalisation/admin/form_field/separator';
    }

    /**
     * Return field template
     *
     * @return string
     */
    protected function getFieldTemplate()
    {
        return 'subheader.twig';
    }
}
