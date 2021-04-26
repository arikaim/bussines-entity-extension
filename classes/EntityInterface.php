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
}
