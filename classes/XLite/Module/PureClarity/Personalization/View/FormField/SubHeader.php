<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE for license details.
 */

namespace XLite\Module\PureClarity\Personalization\View\FormField;

use XLite\View\FormField\Separator\ASeparator;

/**
 * PureClarity SubHeader, for signup form (as there's JS that removes normal separator headers from popups)
 */
class SubHeader extends ASeparator
{
    /**
     * Returns the PureClarity module templates directory
     *
     * @return string
     */
    protected function getDir() : string
    {
        return 'modules/PureClarity/Personalization/admin/form_field/separator';
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
