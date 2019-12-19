<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\View\FormModel\Product;

/**
 * Product form model
 */
abstract class Info extends \XLite\View\FormModel\Product\Info implements \XLite\Base\IDecorator
{
    protected function defineFields()
    {
        $schema = parent::defineFields();

        $schema['pureclarity']['searchTags'] = [
            'label'     => static::t('Search Tags'),
            'position'  => 100,
        ];

        $schema['pureclarity']['excludeFromFeed'] = [
            'label'     => static::t('Exclude from Product Feed'),
            'type'      => 'XLite\View\FormModel\Type\SwitcherType',
            'position'  => 200,
        ];

        $schema['pureclarity']['excludeFromRecommenders'] = [
            'label'     => static::t('Exclude from Recommenders'),
            'type'      => 'XLite\View\FormModel\Type\SwitcherType',
            'position'  => 300,
        ];

        $schema['pureclarity']['recommenderStartDate'] = [
            'label'     => static::t('Show in Recommenders Start Date'),
            'type'      => 'XLite\View\FormModel\Type\DatepickerType',
            'position'  => 400,
        ];

        $schema['pureclarity']['recommenderEndDate'] = [
            'label'     => static::t('Show in Recommenders End Date Date'),
            'type'      => 'XLite\View\FormModel\Type\DatepickerType',
            'position'  => 500,
        ];

        $schema['pureclarity']['newArrival'] = [
            'label'     => static::t('New Arrival'),
            'type'      => 'XLite\View\FormModel\Type\SwitcherType',
            'position'  => 600,
        ];

        $schema['pureclarity']['onOffer'] = [
            'label'     => static::t('On Offer'),
            'type'      => 'XLite\View\FormModel\Type\SwitcherType',
            'position'  => 700,
        ];

        return $schema;
    }

    /**
     * @return array
     */
    protected function defineSections()
    {
        return array_merge(
            parent::defineSections(),
            [
                'pureclarity' => [
                    'label'    => static::t('PureClarity'),
                    'position' => 400,
                ]
            ]
        );
    }
}
