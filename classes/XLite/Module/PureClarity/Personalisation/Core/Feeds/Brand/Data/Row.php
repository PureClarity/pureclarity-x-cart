<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\Core\Feeds\Brand\Data;

use XLite\Base\Singleton;
use XLite\Model\Category;
use XLite\Module\PureClarity\Personalisation\Core\Feeds\FeedRowDataInterface;

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
        $resizedURL = '';
        if ($row->getImage()) {
            list(
                $usedWidth,
                $usedHeight,
                $resizedURL,
                $retinaResizedURL
                ) = $row->getImage()->getResizedURL(244, 244);
        }

        return [
            'Id' => (string)$row->getId(),
            'DisplayName' => $row->getName(),
            'Image' => $resizedURL,
            'Description' => $row->getDescription(),
            'Link' => html_entity_decode($row->getFrontURL())
        ];
    }
}
