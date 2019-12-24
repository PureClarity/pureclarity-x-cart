<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\Model\DTO\Product;

use XLite\Core\Database;
use XLite\Model\Product;

/**
 * Abstract widget
 */
abstract class Info extends \XLite\Model\DTO\Product\Info implements \XLite\Base\IDecorator
{
    /**
     * @param mixed|\XLite\Model\Product $object
     */
    protected function init($object)
    {
        parent::init($object);

        $this->pureclarity->excludeFromFeed = $object->getPureClarityExcludeFromFeed();
        $this->pureclarity->excludeFromRecommenders = $object->getPureClarityExcludeFromRecommenders();
        $this->pureclarity->recommenderStartDate = $object->getPureClarityRecommenderStartDate();
        $this->pureclarity->recommenderEndDate = $object->getPureClarityRecommenderEndDate();
        $this->pureclarity->newArrival = $object->getPureClarityNewArrival();
        $this->pureclarity->onOffer = $object->getPureClarityOnOffer();
        $this->pureclarity->searchTags = $object->getPureClaritySearchTags();
    }

    /**
     * @param Product    $object
     * @param array|null $rawData
     */
    public function populateTo($object, $rawData = null)
    {
        parent::populateTo($object, $rawData);

        $object->setPureClarityExcludeFromFeed((boolean) $this->pureclarity->excludeFromFeed);
        $object->setPureClarityExcludeFromRecommenders((boolean) $this->pureclarity->excludeFromRecommenders);
        $object->setPureClarityRecommenderStartDate($this->pureclarity->recommenderStartDate ?: '');
        $object->setPureClarityRecommenderEndDate($this->pureclarity->recommenderEndDate ?: '');
        $object->setPureClarityNewArrival((boolean) $this->pureclarity->newArrival);
        $object->setPureClarityOnOffer((boolean) $this->pureclarity->onOffer);
        $object->setPureClaritySearchTags((string) $this->pureclarity->searchTags);
    }

    /**
     * @param \XLite\Model\Product $object
     * @param array|null           $rawData
     */
    public function afterCreate($object, $rawData = null)
    {
        parent::afterCreate($object, $rawData);
        $this->processDelta($object);
    }

    /**
     * @param \XLite\Model\Product $object
     * @param array|null           $rawData
     */
    public function afterUpdate($object, $rawData = null)
    {
        parent::afterUpdate($object, $rawData);
        $this->processDelta($object);
    }

    /**
     * @param \XLite\Model\Product $product
     */
    public function processDelta($product)
    {
        $repo = Database::getRepo('XLite\Module\PureClarity\Personalisation\Model\Product\Delta');
        $row = $repo->findOneBy(['productId' => $product->getId()]);

        if (!empty($row)) {
            $repo->updateById($row->getId(), ['type' => 'A']);
        } else {
            $repo->insert(
                [
                    'productId' => $product->getId(),
                    'type' => 'A'
                ]
            );
        }
    }
}
