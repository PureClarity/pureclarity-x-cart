<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE for license details.
 */

namespace XLite\Module\PureClarity\Personalization\Model\Repo;

use XLite\Core\Database;
use XLite\Model\AEntity;
use XLite\Module\PureClarity\Personalization\Core\PureClarity;

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
     * @param AEntity|\XLite\Model\Repo\Product $entity Entity to detach
     *
     * @return void
     */
    protected function performDelete(AEntity $entity)
    {
        $this->processDelta($entity);
        parent::performDelete($entity);
    }

    /**
     * @param $product
     */
    public function processDelta($product) : void
    {
        $pc = PureClarity::getInstance();
        if ($pc->isActive()) {
            $repo = Database::getRepo('XLite\Module\PureClarity\Personalization\Model\Product\Delta');
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
