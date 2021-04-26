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

use Arikaim\Extensions\Entity\Models\Entity;
use Arikaim\Core\Db\Traits\Uuid;
use Arikaim\Core\Db\Traits\Find;
use Arikaim\Core\Db\Traits\DateCreated;

/**
 * Customers model class
 */
class Customers extends Model  
{
    use 
        Uuid,      
        DateCreated,     
        Find;
    
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'customers';

    /**
     * Fillable attributes
     *
     * @var array
     */
    protected $fillable = [
        'entity_id',       
        'date_created'          
    ];
    
    /**
     * Disable timestamps
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Get entity relation
     *
     * @return Relation|null
     */
    public function entity()
    {
        return $this->belongsTo(Entity::class,'entity_id');
    }
}
