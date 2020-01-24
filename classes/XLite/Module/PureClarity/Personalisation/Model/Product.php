<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalization\Model;

abstract class Product extends \XLite\Model\Product implements \XLite\Base\IDecorator
{
    /**
     * @Column (type="boolean")
     */
    protected $pureclarityExcludeFromFeed = false;

    /**
     * @Column (type="boolean")
     */
    protected $pureclarityExcludeFromRecommenders = false;

    /**
     * @Column (type="boolean")
     */
    protected $pureclarityRecommenderDateRange = false;

    /**
     * @Column (type="integer")
     */
    protected $pureclarityRecommenderStartDate = '';

    /**
     * @Column (type="integer")
     */
    protected $pureclarityRecommenderEndDate = '';

    /**
     * @Column (type="boolean")
     */
    protected $pureclarityNewArrival = false;

    /**
     * @Column (type="boolean")
     */
    protected $pureclarityOnOffer = false;

    /**
     * @Column (type="string")
     */
    protected $pureclaritySearchTags = '';

    /**
     * @return bool
     */
    public function getPureClarityExcludeFromFeed()
    {
        return $this->pureclarityExcludeFromFeed;
    }

    /**
     * @param bool $value
     * @return $this|\XLite\Model\Product
     */
    public function setPureClarityExcludeFromFeed($value)
    {
        $this->pureclarityExcludeFromFeed = $value;
        return $this;
    }

    /**
     * @return bool
     */
    public function getPureClarityExcludeFromRecommenders()
    {
        return $this->pureclarityExcludeFromRecommenders;
    }

    /**
     * @param bool $value
     * @return $this|\XLite\Model\Product
     */
    public function setPureClarityExcludeFromRecommenders($value)
    {
        $this->pureclarityExcludeFromRecommenders = $value;
        return $this;
    }

    /**
     * @return bool
     */
    public function getPureClarityRecommenderDateRange()
    {
        return $this->pureclarityRecommenderDateRange;
    }

    /**
     * @param bool $value
     * @return $this|\XLite\Model\Product
     */
    public function setPureClarityRecommenderDateRange($value)
    {
        $this->pureclarityRecommenderDateRange = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getPureClarityRecommenderStartDate()
    {
        return $this->pureclarityRecommenderStartDate;
    }

    /**
     * @param string $value
     * @return $this|\XLite\Model\Product
     */
    public function setPureClarityRecommenderStartDate($value)
    {
        $this->pureclarityRecommenderStartDate = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getPureClarityRecommenderEndDate()
    {
        return $this->pureclarityRecommenderEndDate;
    }

    /**
     * @param string $value
     * @return $this|\XLite\Model\Product
     */
    public function setPureClarityRecommenderEndDate($value)
    {
        $this->pureclarityRecommenderEndDate = $value;
        return $this;
    }

    /**
     * @return bool
     */
    public function getPureClarityNewArrival()
    {
        return $this->pureclarityNewArrival;
    }

    /**
     * @param bool $value
     * @return $this|\XLite\Model\Product
     */
    public function setPureClarityNewArrival($value)
    {
        $this->pureclarityNewArrival = $value;
        return $this;
    }

    /**
     * @return bool
     */
    public function getPureClarityOnOffer()
    {
        return $this->pureclarityOnOffer;
    }

    /**
     * @param bool $value
     * @return $this|\XLite\Model\Product
     */
    public function setPureClarityOnOffer($value)
    {
        $this->pureclarityOnOffer = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getPureClaritySearchTags()
    {
        return $this->pureclaritySearchTags;
    }

    /**
     * @param string $value
     * @return $this|\XLite\Model\Product
     */
    public function setPureClaritySearchTags($value)
    {
        $this->pureclaritySearchTags = $value;
        return $this;
    }
}
