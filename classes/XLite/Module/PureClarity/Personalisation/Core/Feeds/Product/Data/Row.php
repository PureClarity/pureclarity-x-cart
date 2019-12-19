<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\Core\Feeds\Product\Data;

use XLite\Base\Singleton;
use XLite\Core\Database;
use XLite\Model\Category;
use XLite\Model\Product;
use XLite\Module\PureClarity\Personalisation\Core\Feeds\FeedRowDataInterface;

/**
 * class Row
 *
 * PureClarity Product Feed Row Data class
 */
class Row extends Singleton implements FeedRowDataInterface
{
    /** @var Category[] */
    protected $excludedCats;

    /**
     * @param object|Product $row
     *
     * @return mixed[]|null
     */
    public function getRowData($row) : array
    {
        if ($row->getPureClarityExcludeFromFeed()) {
            return [];
        }

        $currencyCode = \XLite::getInstance()->getCurrency()->getCode();

        $categories = $row->getCategories();

        $categoryIds = [];
        foreach ($categories as $category) {
            $categoryIds[] = $category->getId();
        }

        $excludedCats = $this->getExcludedCategories();
        foreach ($excludedCats as $excludedCat) {
            if (in_array($excludedCat->getId(), $categoryIds)) {
                // product is in an excluded Category
                return [];
            }
        }

        $resizedURL = '';

        if ($row->getImage()) {
            list(
                $usedWidth,
                $usedHeight,
                $resizedURL,
                $retinaResizedURL
                ) = $row->getImage()->getResizedURL(262, 280);
        }

        $data = [
            'Id' => $row->getId(),
            'Sku' => $row->getSku(),
            'Title' => $row->getName(),
            'Description' => [
                $row->getCommonDescription(),
                $row->getProcessedBriefDescription()
            ],
            'Link' => $row->getFrontURL(),
            'Image' => $resizedURL,
            'Categories' => $categoryIds,
            'InStock' => $row->isOutOfStock() ? 'false' : 'true',
            'Prices' => [
                $row->getPrice() . ' ' . $currencyCode
            ],
            'SearchTags' => $row->getPureClaritySearchTags(),
            'ExcludeFromRecommenders' => $row->getPureClarityExcludeFromRecommenders(),
            'NewArrival' => $row->getPureClarityNewArrival(),
            'OnOffer' => $row->getPureClarityOnOffer(),
        ];

        if ($row->getPureClarityRecommenderStartDate()) {
            $data['StartDate'] = $row->getPureClarityRecommenderStartDate();
        }

        if ($row->getPureClarityRecommenderEndDate()) {
            $data['EndDate'] = $row->getPureClarityRecommenderEndDate();
        }

        $atts = $row->getAttributeValueS();

        foreach ($atts as $att) {
            /** @var $att \XLite\Model\AttributeValue\AttributeValueSelect */
            $data[str_replace(' ', '_', $att->getAttribute()->getName())] = $att->getAttributeOption()->getName();
        }

        $atts = $row->getAttributeValueH();

        foreach ($atts as $att) {
            /** @var $att \XLite\Model\AttributeValue\AttributeValueHidden */
            $data[str_replace(' ', '_', $att->getAttribute()->getName())] = $att->getAttributeOption()->getName();
        }

        $atts = $row->getAttributeValueC();
        foreach ($atts as $att) {
            /** @var $att \XLite\Model\AttributeValue\AttributeValueCheckbox */
            $data[str_replace(' ', '_', $att->getAttribute()->getName())] = $att->getValue();
        }

        $atts = $row->getAttributeValueT();

        foreach ($atts as $att) {
            $data[str_replace(' ', '_', $att->getAttribute()->getName())] = $att->getValue();
            /** @var $att \XLite\Model\AttributeValue\AttributeValueText */
        }

        return $data;
    }

    /**
     * @return Category[]
     */
    protected function getExcludedCategories()
    {
        if ($this->excludedCats === null) {
            $this->excludedCats = Database::getRepo('XLite\Model\Category')->findBy(
                [
                    'pureclarityExcludeProducts' => '1'
                ]
            );
        }

        return $this->excludedCats;
    }
}
