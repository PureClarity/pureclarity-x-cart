<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\Core\Feeds\Category\Data;

use XLite\Base\Singleton;
use XLite\Model\Category;
use XLite\Module\PureClarity\Personalisation\Core\Feeds\FeedRowDataInterface;

/**
 * class Row
 *
 * PureClarity Category Feed Row Data class
 */
class Row extends Singleton implements FeedRowDataInterface
{
    /**
     * Processes the provided Category into an array in the format required for the PureClarity Category Feed
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

        $data = [
            'Id' => (string)$row->getId(),
            'DisplayName' => $row->getName(),
            'Image' => $resizedURL,
            'Link' => html_entity_decode($row->getFrontURL()),
            'ParentIds' => $this->getParents($row->getId(), $row->getPath()),
            'Description' => $row->getDescription(),
        ];

        if ($row->getPureclarityExcludeFromRecommenders()) {
            $data["ExcludeFromRecommenders"] = true;
        }

        return $data;
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
