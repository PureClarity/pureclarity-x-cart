<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE for license details.
 */

namespace XLite\Module\PureClarity\Personalization\View\FormModel\Product;

/**
 * Class Info
 *
 * Decorator of \XLite\View\FormModel\Product\Info
 *
 * Adds PureClarity form fields to product edit page
 */
abstract class Info extends \XLite\View\FormModel\Product\Info implements \XLite\Base\IDecorator
{
    /**
     * Adds PureClarity form fields to product edit form definition
     *
     * @return array
     */
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

        $schema['pureclarity']['recommenderDateRange'] = [
            'label'     => static::t('Show in recommenders for set time period'),
            'type'      => 'XLite\View\FormModel\Type\SwitcherType',
            'position'  => 400
        ];

        $schema['pureclarity']['recommenderStartDate'] = [
            'label'     => static::t('Show in Recommenders Start Date'),
            'type'      => 'XLite\View\FormModel\Type\DatepickerType',
            'position'  => 500,
            'show_when'          => [
                'pureclarity' => [
                    'recommenderDateRange' => '1',
                ],
            ],
        ];

        $schema['pureclarity']['recommenderEndDate'] = [
            'label'     => static::t('Show in Recommenders End Date Date'),
            'type'      => 'XLite\View\FormModel\Type\DatepickerType',
            'position'  => 600,
            'show_when'          => [
                'pureclarity' => [
                    'recommenderDateRange' => '1',
                ],
            ],
        ];

        $schema['pureclarity']['newArrival'] = [
            'label'     => static::t('New Arrival'),
            'type'      => 'XLite\View\FormModel\Type\SwitcherType',
            'position'  => 700,
        ];

        $schema['pureclarity']['onOffer'] = [
            'label'     => static::t('On Offer'),
            'type'      => 'XLite\View\FormModel\Type\SwitcherType',
            'position'  => 800,
        ];

        return $schema;
    }

    /**
     * Adds PureClarity section to product edit page
     *
     * @return array
     */
    protected function defineSections()
    {
        return array_replace(parent::defineSections(), [
            'pureclarity' => [
                'label'    => static::t('PureClarity'),
                'position' => 400,
            ]
        ]);
    }
}
