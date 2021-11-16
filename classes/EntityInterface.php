<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Entity\Classes;

/**
 * Entity interface  
*/
interface EntityInterface
{
    const TYPE_ORGANIZATION = 'organization';
    const TYPE_PERSON       = 'person';

    const ROLE_CUSTOMER = 'customer';
    const ROLE_VENDOR   = 'vendor';
    const ROLE_SUPPLIER = 'supplier';
    const ROLE_OWNER    = 'owner';
    const ROLE_EMPLOYEE = 'employee';
    
    /**
     * Get name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Get type
     *
     * @return string
     */
    public function getType(): string;
}
