<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE for license details.
 */

namespace XLite\Module\PureClarity\Personalization\Core\Feeds\Product\Data;

use XLite;
use XLite\Base\Singleton;
use XLite\Core\Config;
use XLite\Core\ConfigParser;
use XLite\Core\Converter;
use XLite\Core\Database;
use XLite\Core\Layout;
use XLite\Model\AttributeValue\AttributeValueCheckbox;
use XLite\Model\AttributeValue\AttributeValueHidden;
use XLite\Model\AttributeValue\AttributeValueSelect;
use XLite\Model\AttributeValue\AttributeValueText;
use XLite\Model\Category;
use XLite\Model\Membership;
use XLite\Model\Product;
use XLite\Module\PureClarity\Personalization\Core\Feeds\FeedRowDataInterface;
use XLite\Module\PureClarity\Personalization\Core\PureClarity;
use XLite\Module\XC\ProductVariants\Model\ProductVariant;

/**
 * class Row
 *
 * PureClarity Product Feed Row Data class
 */
class Row extends Singleton implements FeedRowDataInterface
{
    /** @var Category[] */
    protected $excludedCats;

    /** @var string */
    protected $currencyCode;

    /** @var mixed[] */
    protected $rowData;

    /** @var Membership[] */
    protected $memberships;

    /** @var bool */
    protected $isHttps;

    /** @var string */
    protected $httpsDomain;

    /** @var string */
    protected $httpDomain;

    /**
     * Processes the provided Product into an array in the format required for the PureClarity Product Feed
     *
     * @param object|Product $row
     *
     * @return mixed[]
     */
    public function getRowData($row) : array
    {
        $this->rowData = [];
        
        if ($this->excludeRow($row)) {
            return $this->rowData;
        }

        $this->loadHttpConfig();
        $this->buildBaseData($row);
        $this->buildPriceData($row);
        $this->buildVariantData($row);
        $this->buildAttributeData($row);

        return $this->rowData;
    }

    /**
     * returns whether a row should be excluded from the feed data or not
     *
     * @param object|Product $row
     *
     * @return bool
     */
    protected function excludeRow($row) : bool
    {
        $exclude = false;
        if ($row->getPureClarityExcludeFromFeed()) {
            $exclude = true;
        } else {
            $excludedCats = $this->getExcludedCategories();

            if (count($excludedCats) > 0) {
                $categories = $row->getCategories();

                $categoryIds = [];
                foreach ($categories as $category) {
                    $categoryIds[] = $category->getId();
                }

                foreach ($excludedCats as $excludedCat) {
                    if (in_array($excludedCat->getId(), $categoryIds)) {
                        // product is in an excluded Category
                        $exclude = true;
                    }
                }
            }
        }

        return $exclude;
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
    
    /**
     * Adds Base product data to the row data array
     *
     * @param object|Product $row
     *
     * @return void
     */
    protected function buildBaseData($row) : void
    {
        $categories = $row->getCategories();

        $categoryIds = [];
        foreach ($categories as $category) {
            $categoryIds[] = $category->getId();
        }

        $this->rowData = [
            'Id' => $row->getId(),
            'Sku' => $row->getSku(),
            'Title' => $row->getName(),
            'Link' => $this->getFrontURL($row),
            'Image' => $this->getProductImageURL($row),
            'Categories' => $categoryIds,
            'InStock' => $row->isOutOfStock() ? 'false' : 'true',
            'SearchTags' => $row->getPureClaritySearchTags(),
            'ExcludeFromRecommenders' => $row->getPureClarityExcludeFromRecommenders(),
            'NewArrival' => $row->getPureClarityNewArrival(),
            'OnOffer' => $row->getPureClarityOnOffer(),
        ];

        if ($row->getPureClarityRecommenderDateRange()) {
            if ($row->getPureClarityRecommenderStartDate()) {
                $this->rowData['StartDate'] = date('Y-m-d H:i:s', $row->getPureClarityRecommenderStartDate());
            }

            if ($row->getPureClarityRecommenderEndDate()) {
                $this->rowData['EndDate'] = date('Y-m-d H:i:s', $row->getPureClarityRecommenderEndDate());
            }
        }
    }

    /**
     * Gets the storefront url for the product
     *
     * @param object|Product $row
     *
     * @return string
     */
    protected function getFrontURL($row) : string
    {
        $url = $row->getFrontURL(false, true);

        if ($this->isHttps) {
            $url = str_replace(['http://', $this->httpDomain], ['https://', $this->httpsDomain], $url);
        }

        return html_entity_decode($url);
    }

    /**
     * Loads the config for https & domains
     */
    protected function loadHttpConfig()
    {
        if ($this->isHttps === null) {
            $this->isHttps = Config::getInstance()->Security->customer_security;
            $this->httpsDomain = ConfigParser::getOptions(['host_details', 'https_host']);
            $this->httpDomain = ConfigParser::getOptions(['host_details', 'http_host']);
        }
    }

    /**
     * Gets an image url for the product
     *
     * @param object|Product $row
     *
     * @return string
     */
    protected function getProductImageURL($row) : string
    {
        $imageUrl = '';
        $image = $row->getImage();
        if ($image) {
            $imageUrl = $image->getFrontURL();
        } else {
            $url = \XLite::getInstance()->getOptions(['images', 'default_image']);
            if (!Converter::isURL($url)) {
                $imageUrl = Layout::getInstance()->getResourceWebPath(
                    $url,
                    Layout::WEB_PATH_OUTPUT_FULL,
                    'frontend'
                );
            }
        }

        if ($this->isHttps) {
            $imageUrl = str_replace(['http://', $this->httpDomain], ['https://', $this->httpsDomain], $imageUrl);
        }

        return $imageUrl;
    }

    /**
     * Adds Price data to the row data array
     *
     * @param object|Product $row
     *
     * @return void
     */
    protected function buildPriceData($row) : void
    {
        $currencyCode = $this->getCurrencyCode();
        $this->rowData['Prices'] = [$row->getPrice() . ' ' . $currencyCode];

        if ($row->getParticipateSale() && $row->getSalePriceValue() && $row->getSalePriceValue() !== $row->getPrice()) {
            $this->rowData['SalePrices'] = [
                $row->getSalePriceValue() . ' ' . $currencyCode
            ];
        }

        if ($row->isWholesalePricesEnabled()) {
            $groupPrices = [];
            foreach ($this->getMemberships() as $membership) {
                $price = $row->getWholesalePrice($membership);
                if ($price) {
                    $this->rowData['GroupPrices'][$membership->getMembershipId()]['Prices'][] = $price
                                                                                              . ' '
                                                                                              . $currencyCode;
                }
            }

            if (!empty($groupPrices)) {
                $this->rowData['GroupPrices'] = $groupPrices;
            }
        }
    }

    /**
     * Adds Variant data to the row data array
     *
     * @param object|Product $row
     *
     * @return void
     */
    protected function buildVariantData($row) : void
    {
        if (method_exists($row, 'getVariants')) {
            $variants = $row->getVariants();
            if ($variants && $variants->count() > 0) {
                $currencyCode = $this->getCurrencyCode();
                $this->rowData['AssociatedSkus'] = [];

                $pc = PureClarity::getInstance();
                $excludeOutOfStock = $pc->getConfigFlag(PureClarity::CONFIG_FEEDS_PRODUCT_OOS_EXCLUDE);

                foreach ($variants as $variant) {
                    /** @var ProductVariant $variant */

                    if ($excludeOutOfStock && $variant->isOutOfStock()) {
                        continue;
                    }

                    $this->rowData['AssociatedSkus'][] = $variant->getSku() ?: $variant->getVariantId();
                    $this->rowData['Prices'][] = $variant->getPrice() . ' ' . $currencyCode;


                    if ($variant->getParticipateSale() &&
                        $variant->getSalePriceValue() &&
                        $variant->getSalePriceValue() !== $variant->getPrice()) {
                        $this->rowData['SalePrices'] = [
                            $this->rowData->getSalePriceValue() . ' ' . $currencyCode
                        ];
                    }

                    if ($variant->getProduct()->isWholesalePricesEnabled()) {
                        foreach ($this->getMemberships() as $membership) {
                            $price = Database::getRepo(
                                'XLite\Module\CDev\Wholesale\Model\ProductVariantWholesalePrice'
                            )->getPrice(
                                $variant,
                                $variant->getProduct()->getWholesaleQuantity()
                                    ?: $variant->getProduct()->getMinQuantity($membership),
                                $membership
                            );

                            if ($price) {
                                $this->rowData['GroupPrices'][$membership->getMembershipId()]['Prices'][] = $price
                                  . ' '
                                  . $currencyCode;
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Adds Attribute data to the row data array
     *
     * @param object|Product $row
     *
     * @return void
     */
    protected function buildAttributeData($row) : void
    {
        $selectAttributes = $row->getAttributeValueS();

        foreach ($selectAttributes as $att) {
            /** @var $att AttributeValueSelect */
            $data[str_replace(' ', '_', $att->getAttribute()->getName())] = $att->getAttributeOption()->getName();
        }

        $hiddenAttributes = $row->getAttributeValueH();

        foreach ($hiddenAttributes as $att) {
            /** @var $att AttributeValueHidden */
            $data[str_replace(' ', '_', $att->getAttribute()->getName())] = $att->getAttributeOption()->getName();
        }

        $checkboxAttributes = $row->getAttributeValueC();
        foreach ($checkboxAttributes as $att) {
            /** @var $att AttributeValueCheckbox */
            $data[str_replace(' ', '_', $att->getAttribute()->getName())] = $att->getValue();
        }

        $textAttributes = $row->getAttributeValueT();

        foreach ($textAttributes as $att) {
            $data[str_replace(' ', '_', $att->getAttribute()->getName())] = $att->getValue();
            /** @var $att AttributeValueText */
        }
    }

    /**
     * Returns an array of all memberships in the store
     *
     * @return Membership[]
     */
    protected function getMemberships() : array
    {
        if ($this->memberships === null) {
            $this->memberships = Database::getRepo('XLite\Model\Membership')->findAll();
        }
        
        return $this->memberships;
    }

    /**
     * Gets the store currency code
     *
     * @return string
     */
    protected function getCurrencyCode() : string
    {
        if ($this->currencyCode === null) {
            $this->currencyCode = XLite::getInstance()->getCurrency()->getCode();
        }

        return $this->currencyCode;
    }
}
