<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE for license details.
 */

namespace XLite\Module\PureClarity\Personalization\Core\Delta;

use Exception;
use PureClarity\Api\Delta\Type\Product as ProductDelta;
use XLite\Base\Singleton;
use XLite\Core\Database;
use XLite\Module\PureClarity\Personalization\Core\Feeds\Product\Data\Row;
use XLite\Module\PureClarity\Personalization\Core\PureClarity;
use XLite\Module\PureClarity\Personalization\Core\State;
use XLite\Module\PureClarity\Personalization\Model\Product\Delta;

/**
 * class Product
 *
 * PureClarity Product Delta class
 */
class Product extends Singleton
{
    /**
     * Runs the product delta
     */
    public function runDelta() : void
    {
        try {
            $pc = PureClarity::getInstance();
            $active = $pc->isActive();
            $deltasEnabled = $pc->getConfigFlag(PureClarity::CONFIG_FEEDS_DELTAS);

            if ($deltasEnabled === false || $active === false) {
                return;
            }

            $productData = Row::getInstance();
            $deltaRepo = Database::getRepo('XLite\Module\PureClarity\Personalization\Model\Product\Delta');
            $deltas = $deltaRepo->findAll();
            $productRepo = Database::getRepo('XLite\Model\Product');

            if (count($deltas) > 0) {
                $deltaHandler = new ProductDelta(
                    $pc->getConfig(PureClarity::CONFIG_ACCESS_KEY),
                    $pc->getConfig(PureClarity::CONFIG_SECRET_KEY),
                    $pc->getConfig(PureClarity::CONFIG_REGION)
                );

                foreach ($deltas as $delta) {
                    /** @var $delta Delta */
                    if ($delta->getterProperty('type') === 'D') {
                        $deltaHandler->addDelete($delta->getterProperty('productId'));
                    } else {
                        /** @var $product \XLite\Model\Product */
                        $product = $productRepo->findOneBy(
                            [
                                'product_id' => $delta->getterProperty('productId')
                            ]
                        );

                        if (!$product) {
                            $deltaHandler->addDelete($delta->getterProperty('productId'));
                        } else {
                            $data = $productData->getRowData($product);
                            if ($data !== null) {
                                $deltaHandler->addData($data);
                            } else {
                                $deltaHandler->addDelete($delta->getterProperty('productId'));
                            }
                        }
                    }
                }

                $deltaHandler->send();
                $deltaRepo->deleteInBatch($deltas);
            }
        } catch (Exception $e) {
            $state = State::getInstance();
            $state->setStateValue('product_delta_error', $e->getMessage());
        }
    }
}
