<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalization\Core\Feeds\Brand\Data;

use XLite\Base\Singleton;
use XLite\Core\Converter;
use XLite\Core\Layout;
use XLite\Model\Category;
use XLite\Module\PureClarity\Personalization\Core\Feeds\FeedRowDataInterface;

/**
 * class Row
 *
 * PureClarity Brand Feed Row Data class
 */
class Row extends Singleton implements FeedRowDataInterface
{
    /**
     * Processes the provided Category into an array in the format required for the PureClarity Brand Feed
     *
     * @param object|Category $row
     *
     * @return mixed[]
     */
    public function getRowData($row) : array
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

        return [
            'Id' => (string)$row->getId(),
            'DisplayName' => $row->getName(),
            'Image' => $imageUrl,
            'Description' => $row->getDescription(),
            'Link' => html_entity_decode($row->getFrontURL())
        ];
    }
}
