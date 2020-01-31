<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE for license details.
 */

namespace XLite\Module\PureClarity\Personalization\Core\Feeds\Category\Data;

use XLite\Base\Singleton;
use XLite\Core\Config;
use XLite\Core\ConfigParser;
use XLite\Core\Converter;
use XLite\Core\Layout;
use XLite\Model\Category;
use XLite\Module\PureClarity\Personalization\Core\Feeds\FeedRowDataInterface;

/**
 * class Row
 *
 * PureClarity Category Feed Row Data class
 */
class Row extends Singleton implements FeedRowDataInterface
{
    /** @var bool */
    protected $isHttps;

    /** @var string */
    protected $httpsDomain;

    /** @var string */
    protected $httpDomain;

    /**
     * Processes the provided Category into an array in the format required for the PureClarity Category Feed
     *
     * @param object|Category $row
     *
     * @return mixed[]
     */
    public function getRowData($row) : array
    {
        $this->loadHttpConfig();

        $data = [
            'Id' => (string)$row->getId(),
            'DisplayName' => $row->getName(),
            'Image' => $this->getImageURL($row),
            'Link' => $this->getFrontURL($row),
            'ParentIds' => $this->getParents($row->getId(), $row->getPath()),
            'Description' => $row->getDescription(),
        ];

        if ($row->getPureclarityExcludeFromRecommenders()) {
            $data["ExcludeFromRecommenders"] = true;
        }

        return $data;
    }

    /**
     * Gets an image url for the category
     *
     * @param object|Category $row
     *
     * @return string
     */
    protected function getImageURL($row) : string
    {
        $imageUrl = '';
        if ($row->getImage()) {
            list(
                $usedWidth,
                $usedHeight,
                $imageUrl,
                $retinaResizedURL
                ) = $row->getImage()->getResizedURL(244, 244);
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
     * Gets the storefront url for the category
     *
     * @param object|Category $row
     *
     * @return string
     */
    protected function getFrontURL($row) : string
    {
        $url = $row->getFrontURL(true);

        $this->loadHttpConfig();

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
     * @param integer $currentId
     * @param Category[] $parents
     * @return string[]
     */
    protected function getParents($currentId, $parents)
    {
        $parentIds = [];

        foreach ($parents as $parent) {
            /** @var $parent Category */
            if ($parent->getId() !== $currentId) {
                $parentIds[] = (string)$parent->getId();
            }
        }

        return $parentIds;
    }
}
