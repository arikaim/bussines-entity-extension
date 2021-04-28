<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Entity\Models;

use Illuminate\Database\Eloquent\Model;

use Arikaim\Core\Db\Traits\Uuid;
use Arikaim\Core\Db\Traits\Find;
use Arikaim\Core\Db\Traits\DateCreated;
use Arikaim\Extensions\Address\Models\Traits\AddressRelation;

/**
 * Organization model class
 */
class Organization extends Model  
{
    use 
        Uuid,
        DateCreated,     
        AddressRelation,      
        Find;
    
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'organization';

    /**
     * Fillable attributes
     *
     * @var array
     */
    protected $fillable = [
        'note',     
        'address_id',
        'user_id',
        'date_created'          
    ];
    
    /**
     * Disable timestamps
     *
     * @var boolean
     */
    public $timestamps = false;
}
