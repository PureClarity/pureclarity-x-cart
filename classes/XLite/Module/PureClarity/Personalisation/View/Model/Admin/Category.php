<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\View\Model\Admin;

/**
 * Category model widget extention
 */
class Category extends \XLite\View\Model\Category implements \XLite\Base\IDecorator
{
    /**
     * OG widgets into the default section
     *
     * @param array $params
     * @param array $sections
     */
    public function __construct(array $params = array(), array $sections = array())
    {
        parent::__construct($params, $sections);

        $this->schemaDefault['pureclarityExcludeFromFeed'] = [
            static::SCHEMA_LABEL     => static::t('Exclude from PureClarity Category Feed'),
            static::SCHEMA_CLASS      => 'XLite\View\FormField\Input\Checkbox\YesNo',
            static::SCHEMA_REQUIRED => false,
        ];

        $this->schemaDefault['pureclarityExcludeFromRecommenders'] = [
            static::SCHEMA_LABEL    => static::t('Exclude from PureClarity Recommenders'),
            static::SCHEMA_CLASS    => 'XLite\View\FormField\Input\Checkbox\YesNo',
            static::SCHEMA_REQUIRED => false,
        ];

        $this->schemaDefault['pureclarityExcludeProducts'] = [
            static::SCHEMA_LABEL       => static::t(
                'Exclude Products in this Category from the PureClarity Product Feed'
            ),
            static::SCHEMA_CLASS      => 'XLite\View\FormField\Input\Checkbox\YesNo',
            static::SCHEMA_REQUIRED   => false,
        ];
    }
}
