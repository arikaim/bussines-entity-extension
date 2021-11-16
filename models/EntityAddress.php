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

use Arikaim\Extensions\Address\Models\Address;
use Arikaim\Core\Db\Model as DbModel;
use Arikaim\Core\Db\Traits\Uuid;
use Arikaim\Core\Db\Traits\Find;

/**
 * Entity address relations model class
 */
class EntityAddress extends Model  
{
    use 
        Uuid,
        Find;
    
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'entity_address';

    /**
     * Fillable attributes
     *
     * @var array
     */
    protected $fillable = [
        'address_type',          
        'entity_id',
        'address_id'          
    ];
    
    /**
     * Disable timestamps
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Address relation
     *
     * @return Relation|null
     */
    public function address() 
    {
        return $this->belongsTo(Address::class,'address_id');
    }

    /**
     * Address query scope
     *
     * @param Builder $query
     * @param integer|null $entityId
     * @param string $type
     * @return Builder
     */
    public function scopeFindAddressQuery($query, string $type, ?int $entityId = null)
    {
        $entityId = $entityId ?? $this->entity_id;

        return $query->where('entity_id','=',$entityId)->where('address_type','=',$type);
    }

    /**
     * Find address
     *
     * @param string $type
     * @param integer|null $entityId
     * @return Model|null
     */
    public function findAddress(string $type, ?int $entityId = null)
    {
        $query = $this->findAddressQuery($type,$entityId);

        return $query->first();
    }

    /**
     * Return  true if address exist
     *
     * @param integer|null $entityId
     * @param string $type
     * @return boolean
     */
    public function hasAddress(string $type, ?int $entityId = null): bool
    {      
        $model = $this->findAddress($type,$entityId);

        return \is_object($model);
    } 

    /**
     * Find or create new address model with relation to entity
     *
     * @param integer|null $entityId
     * @param string $type
     * @return Model|null
     */
    public function findOrCreate(string $type, ?int $entityId = null)
    {
        $entityId = $entityId ?? $this->entity_id;
        $model = $this->findAddress($type,$entityId);
     
        if (\is_object($model) == true) {
            return $model->address;
        }

        $address = Address::create([
            'type' => $type
        ]);       

        $relation = $this->create([
            'entity_id'    => $entityId,
            'address_type' => $type,
            'address_id'   => $address->id
        ]);

        return (\is_object($relation) == true) ? $address : null;
    }
}
