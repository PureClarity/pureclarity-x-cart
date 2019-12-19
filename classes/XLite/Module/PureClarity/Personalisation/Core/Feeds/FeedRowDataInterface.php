<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\Core\Feeds;

interface FeedRowDataInterface
{
    /**
     * @param object $row
     * @return array
     */
    public function getRowData($row) : array;
}
