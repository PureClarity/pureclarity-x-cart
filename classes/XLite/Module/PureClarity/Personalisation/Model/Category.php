<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalization\Model;

use \XLite\Module\PureClarity\Personalization\Core\State;

abstract class Category extends \XLite\Model\Category implements \XLite\Base\IDecorator
{

    /**
     * PureClarity flag - exclude from recommender
     *
     * @var boolean
     *
     * @Column (type="boolean")
     */
    protected $pureclarityExcludeFromRecommenders = false;

    /**
     * PureClarity flag - exclude from feed
     *
     * @var boolean
     *
     * @Column (type="boolean")
     */
    protected $pureclarityExcludeFromFeed = false;

    /**
     * PureClarity flag - exclude products in this category from feed
     *
     * @var boolean
     *
     * @Column (type="boolean")
     */
    protected $pureclarityExcludeProducts = false;


    /**
     * Set pureclarityExcludeFromRecommenders
     *
     * @param boolean $pureclarityExcludeFromRecommenders
     * @return Category
     */
    public function setPureclarityExcludeFromRecommenders($pureclarityExcludeFromRecommenders)
    {
        $this->pureclarityExcludeFromRecommenders = $pureclarityExcludeFromRecommenders;
        return $this;
    }

    /**
     * Get pureclarityExcludeFromRecommenders
     *
     * @return boolean
     */
    public function getPureclarityExcludeFromRecommenders()
    {
        return $this->pureclarityExcludeFromRecommenders;
    }

    /**
     * Set pureclarityExcludeFromFeed
     *
     * @param boolean $pureclarityExcludeFromFeed
     * @return Category
     */
    public function setPureclarityExcludeFromFeed($pureclarityExcludeFromFeed)
    {
        $this->pureclarityExcludeFromFeed = $pureclarityExcludeFromFeed;
        return $this;
    }

    /**
     * Get pureclarityExcludeFromFeed
     *
     * @return boolean
     */
    public function getPureclarityExcludeFromFeed()
    {
        return $this->pureclarityExcludeFromFeed;
    }

    /**
     * Set pureclarityExcludeProducts
     *
     * @param boolean $pureclarityExcludeProducts
     * @return Category
     */
    public function setPureclarityExcludeProducts($pureclarityExcludeProducts)
    {
        $this->pureclarityExcludeProducts = $pureclarityExcludeProducts;
        return $this;
    }

    /**
     * Get pureclarityExcludeProducts
     *
     * @return boolean
     */
    public function getPureclarityExcludeProducts()
    {
        return $this->pureclarityExcludeProducts;
    }


    /**
     * Create entity
     *
     * @return boolean
     */
    public function create()
    {
        $this->processDelta();
        return parent::create();
    }

    /**
     * Update entity
     *
     * @return boolean
     */
    public function update()
    {
        $this->processDelta();
        return parent::update();
    }

    /**
     * Delete entity
     *
     * @return boolean
     */
    public function delete()
    {
        $this->processDelta();
        return parent::delete();
    }

    /**
     * No category info delta, so request category feed
     */
    public function processDelta()
    {
        $state = State::getInstance();
        $stateValue = $state->getStateValue('requested_feeds');
        if (empty($stateValue)) {
            $state->setStateValue('requested_feeds', json_encode(['category']));
        } elseif (strpos($stateValue, 'category') === false) {
            $feeds = json_decode($stateValue);
            $feeds[] = 'category';
            $state->setStateValue('requested_feeds', json_encode($feeds));
        }
    }
}
