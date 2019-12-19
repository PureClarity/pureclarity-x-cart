<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\Model\Product;

/**
 * @Entity
 * @Table  (name="pureclarity_product_delta")
 */
class Delta extends \XLite\Model\AEntity
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
