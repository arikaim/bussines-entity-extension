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
     * @param string|null $type
     * @return Builder
     */
    public function scopeFindAddressQuery($query, ?string $type, ?int $entityId = null)
    {
        $entityId = $entityId ?? $this->entity_id;
        if (empty($type) == false) {
            $query->where('address_type','=',$type);
        }
        if (empty($entityId) == false) {
            $query->where('entity_id','=',$entityId);
        }
        
        return $query;
    }

    /**
     * Find address
     *
     * @param string $type
     * @param integer|null $entityId
     * @return Model|null
     */
    public function findAddress(string $type, ?int $entityId = null): ?object
    {
        return $this->findAddressQuery($type,$entityId)->first();       
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
        return ($this->findAddress($type,$entityId) != null);
    } 

    /**
     * Link existing address
     *
     * @param string       $type
     * @param integer      $addressId
     * @param integer|null $entityId
     * @return boolean
     */
    public function linkAddress(string $type, int $addressId, ?int $entityId = null): bool
    {
        $entityId = $entityId ?? $this->entity_id;
        $model = $this->findAddress($type,$entityId);
        if ($model != null) {
            // address link exists
            return true;
        }

        $relation = $this->create([
            'entity_id'    => $entityId,
            'address_type' => $type,
            'address_id'   => $addressId
        ]);

        return ($relation != null);
    }

    /**
     * Find or create new address model with relation to entity
     *
     * @param integer|null $entityId
     * @param string $type
     * @param int|null $userId
     * @return Model|null
     */
    public function findOrCreate(string $type, ?int $entityId = null, ?int $userId = null): ?object
    {
        $entityId = $entityId ?? $this->entity_id;
        $model = $this->findAddress($type,$entityId);
        if ($model != null) {
            return $model->address;
        }

        $address = Address::create([
            'type'      => $type,
            'user_id'   => $userId
        ]);       

        $relation = $this->create([
            'entity_id'    => $entityId,
            'address_type' => $type,
            'address_id'   => $address->id
        ]);

        return ($relation != null) ? $address : null;
    }
}
