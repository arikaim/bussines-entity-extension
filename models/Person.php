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
use Arikaim\Core\Db\Traits\UserRelation;

/**
 * Person model class
 */
class Person extends Model  
{
    use 
        Uuid,   
        UserRelation,         
        DateCreated,     
        Find;
    
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'person';

    /**
     * Fillable attributes
     *
     * @var array
     */
    protected $fillable = [
        'note',
        'age',       
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
