<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\Core;

use XLite\Base\Singleton;
use XLite\Core\Database;

/**
 *
 */
class State extends Singleton
{
    /**
     * Gets the value for the given state name key
     *
     * @param string $nameKey
     * @return string
     */
    public function getStateValue(string $nameKey) : string
    {
        $repo = Database::getRepo('XLite\Module\PureClarity\Personalisation\Model\State');
        $row = $repo->findOneBy(['name' => $nameKey]);

        $state = '';
        if (!empty($row)) {
            $state = $row->getValue();
        }

        return $state;
    }

    /**
     * Gets the value for the given state name key
     *
     * @param string $nameKey
     * @param string $value
     */
    public function setStateValue(string $nameKey, string $value)
    {
        $repo = Database::getRepo('XLite\Module\PureClarity\Personalisation\Model\State');
        $row = $repo->findOneBy(['name' => $nameKey]);

        if (!empty($row)) {
            $row->setValue($value);
            $repo->updateById($row->getId(), ['value' => $value]);
        } else {
            $repo->insert(
                [
                    'name' => $nameKey,
                    'value' => $value
                ]
            );
        }
    }
}
