<?php
/**
 * Copyright © PureClarity. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace XLite\Module\PureClarity\Personalisation\Model;

/**
 * @Entity
 * @Table  (name="pureclarity_state")
 */
class State extends \XLite\Model\AEntity
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
