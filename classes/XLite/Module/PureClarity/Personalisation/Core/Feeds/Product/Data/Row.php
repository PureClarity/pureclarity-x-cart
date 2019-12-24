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
     * Processes the provided Product into an array in the format required for the PureClarity Product Feed
     *
     * @param object|Product $row
     *
     * @return mixed[]
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

        if ($row->getSalePriceValue() && $row->getSalePriceValue() !== $row->getPrice()) {
            $data['SalePrices'] = [
                $row->getSalePriceValue() . ' ' . $currencyCode
            ];
        }

        $variants = $row->getVariants();
        if ($variants && $variants->count() > 0) {
            $data['AssociatedSkus'] = [];
            foreach ($variants as $variant) {
                /** @var \XLite\Module\XC\ProductVariants\Model\ProductVariant $variant */
                $data['AssociatedSkus'][] = $variant->getSku() ?: $variant->getVariantId();
                $data['Prices'][] = $variant->getPrice() . ' ' . $currencyCode;
                if ($variant->getSalePriceValue() && $variant->getSalePriceValue() !== $variant->getPrice()) {
                    $data['SalePrices'] = [
                        $variant->getSalePriceValue() . ' ' . $currencyCode
                    ];
                }
            }
        }

        if ($row->getPureClarityRecommenderStartDate()) {
            $data['StartDate'] = $row->getPureClarityRecommenderStartDate();
        }

        if ($row->getPureClarityRecommenderEndDate()) {
            $data['EndDate'] = $row->getPureClarityRecommenderEndDate();
        }

        $selectAttributes = $row->getAttributeValueS();

        foreach ($selectAttributes as $att) {
            /** @var $att \XLite\Model\AttributeValue\AttributeValueSelect */
            $data[str_replace(' ', '_', $att->getAttribute()->getName())] = $att->getAttributeOption()->getName();
        }

        $hiddenAttributes = $row->getAttributeValueH();

        foreach ($hiddenAttributes as $att) {
            /** @var $att \XLite\Model\AttributeValue\AttributeValueHidden */
            $data[str_replace(' ', '_', $att->getAttribute()->getName())] = $att->getAttributeOption()->getName();
        }

        $checkboxAttributes = $row->getAttributeValueC();
        foreach ($checkboxAttributes as $att) {
            /** @var $att \XLite\Model\AttributeValue\AttributeValueCheckbox */
            $data[str_replace(' ', '_', $att->getAttribute()->getName())] = $att->getValue();
        }

        $textAttributes = $row->getAttributeValueT();

        foreach ($textAttributes as $att) {
            $data[str_replace(' ', '_', $att->getAttribute()->getName())] = $att->getValue();
            /** @var $att \XLite\Model\AttributeValue\AttributeValueText */
        }

        return $data;
    }

    /**
     * Gets an array of categories that are marked as having their products excluded from the feed
     *
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
