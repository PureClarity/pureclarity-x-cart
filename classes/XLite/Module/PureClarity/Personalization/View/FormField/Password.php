<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE for license details.
 */

namespace XLite\Module\PureClarity\Personalization\View\FormField;

use XLite\View\FormField\Input\Password as XcPassword;

/**
 * PureClarity Password Field type (enforces PureClarity Password policy)
 */
class Password extends XcPassword
{
    /**
     * Assemble validation rules
     *
     * @return array
     */
    protected function assembleValidationRules() : array
    {
        $rules = parent::assembleValidationRules();

        $rules[] = 'custom[pureclarityPassword]';

        return $rules;
    }
}
