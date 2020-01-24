<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalization\Model\Product;

use XLite\Model\AEntity;

/**
 * @Entity
 * @Table  (name="pureclarity_product_delta")
 */
class Delta extends AEntity
{

    /**
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="integer", options={ "unsigned": true })
     */
    protected $id;
 
  
    /**
     * @Column (type="integer", options={ "unsigned": true })
     */
    protected $productId;
 
  
    /**
     * @Column (type="string", length=1)
     */
    protected $type = '';
}
