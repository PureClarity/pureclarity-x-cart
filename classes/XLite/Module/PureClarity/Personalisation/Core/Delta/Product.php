<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\Core\Delta;

use PureClarity\Api\Delta\Type\Product as ProductDelta;
use XLite\Base\Singleton;
use XLite\Core\Config;
use XLite\Core\Database;
use XLite\Module\PureClarity\Personalisation\Core\Feeds\Product\Data;
use XLite\Module\PureClarity\Personalisation\Core\State;
use XLite\Module\PureClarity\Personalisation\Model\Product\Delta;

/**
 * class Runner
 *
 * PureClarity Product Feed Runner class
 */
class Product extends Singleton
{
    public function runDelta()
    {
        try {
            $enabled = Config::getInstance()->PureClarity->Personalisation->pc_enabled;
            $accessKey = Config::getInstance()->PureClarity->Personalisation->pc_access_key;
            $secretKey = Config::getInstance()->PureClarity->Personalisation->pc_secret_key;
            $region = Config::getInstance()->PureClarity->Personalisation->pc_region;

            if (empty($enabled) || empty($accessKey) || empty($secretKey) || empty($region)) {
                return;
            }

            $productData = Data::getInstance();
            $deltaRepo = Database::getRepo('XLite\Module\PureClarity\Personalisation\Model\Product\Delta');
            $deltas = $deltaRepo->findAll();
            $productRepo = Database::getRepo('XLite\Model\Product');

            if (count($deltas) > 0) {
                $deltaHandler = new ProductDelta($accessKey, $secretKey, $region);

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
                            $data = $productData->getProductData($product);
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
        } catch (\Exception $e) {
            $state = State::getInstance();
            $state->setStateValue('product_delta_error', $e->getMessage());
        }
    }
}
