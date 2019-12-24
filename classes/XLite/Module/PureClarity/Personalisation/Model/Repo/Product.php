<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\Model\Repo;

use XLite\Core\Database;
use XLite\Module\PureClarity\Personalisation\Core\PureClarity;

/**
 * Class Product
 *
 * Decorator of \XLite\Model\Repo\Product
 *
 * Adds delta handling for Product deletion
 */
abstract class Product extends \XLite\Model\Repo\Product implements \XLite\Base\IDecorator
{
    /**
     * Delete single entity
     *
     * @param \XLite\Model\AEntity|\XLite\Model\Repo\Product $entity Entity to detach
     *
     * @return void
     */
    protected function performDelete(\XLite\Model\AEntity $entity)
    {
        $this->processDelta($entity);
        parent::performDelete($entity);
    }

    /**
     * @param \XLite\Model\Repo\Product $product
     */
    public function processDelta(\XLite\Model\Repo\Product $product) : void
    {
        $pc = PureClarity::getInstance();
        if ($pc->isActive()) {
            $repo = Database::getRepo('XLite\Module\PureClarity\Personalisation\Model\Product\Delta');
            $row = $repo->findOneBy(['productId' => $product->getId()]);

            if (!empty($row)) {
                $repo->updateById($row->getId(), ['type' => 'D']);
            } else {
                $repo->insert(
                    [
                        'productId' => $product->getId(),
                        'type' => 'D'
                    ]
                );
            }
        }
    }
}
