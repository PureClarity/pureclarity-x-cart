<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE for license details.
 */

namespace XLite\Module\PureClarity\Personalization\Model;

use XLite\Model\AEntity;

/**
 * @Entity
 * @Table  (name="pureclarity_state")
 */
class State extends AEntity
{
    /**
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="integer", options={ "unsigned": true })
     */
    protected $id;
    
    /**
     * @Column (type="string", length=100, unique=true)
     */
    protected $name = '';

    /**
     * @Column (type="text")
     */
    protected $value = '';
}
