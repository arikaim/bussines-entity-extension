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

use Arikaim\Extensions\Entity\Models\EntityAddress;
use Arikaim\Core\Db\Traits\Uuid;
use Arikaim\Core\Db\Traits\Find;
use Arikaim\Core\Db\Traits\Status;
use Arikaim\Core\Db\Traits\DateCreated;
use Arikaim\Core\Db\Traits\DateUpdated;
use Arikaim\Core\Db\Traits\SoftDelete;
use Arikaim\Core\Db\Traits\UserRelation;
use Arikaim\Extensions\Entity\Classes\EntityInterface;

/**
 * Entity model class
 */
class Entity extends Model  
{
    use Uuid,    
        Status,   
        UserRelation,
        DateCreated,
        DateUpdated,
        SoftDelete,
        Find;
    
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'entity';

    /**
     * Fillable attributes
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'relation_type',
        'relation_id',
        'status',
        'user_id',
        'customer',
        'supplier',
        'vendor',
        'employee',
        'seller',
        'date_created',
        'date_updated',
        'date_deleted'       
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
    public function addresses()
    {
        return $this->hasMany(EntityAddress::class,'entity_id');
    }

    /**
     * Delete entity
     *
     * @param integer|null $id
     * @return bool
     */
    public function deleteEntity(?int $id = null): bool
    {       
        $model = (empty($id) == false) ? $this->findById($id) : $this;
        // delete address relations
        $this->address($id)->findAddressQuery(null,$model->id)->delete();
        // delete entity type model
        $type = $this->getEntityTypeModel($model->relation_type,$model->relation_id);
        if ($type != null) {
            $type->delete();
        }
        // delete entity
        return ($model->delete() !== false);
    }

    /**
     * is_person attribute
     *
     * @return boolean
     */
    public function getIsPersonAttribute()
    {
        return ($this->relation_type == 'person');
    } 

    /**
     * Get roles attribute
     *
     * @return array
     */
    public function getRolesAttribute(): array
    {
        $roles = [];

        if ($this->customer == 1) {
            $roles[] = EntityInterface::ROLE_CUSTOMER;
        } 

        if ($this->vendor == 1) {
            $roles[] = EntityInterface::ROLE_VENDOR;
        }

        if ($this->supplier == 1) {
            $roles[] = EntityInterface::ROLE_SUPPLIER;
        }

        if ($this->employee == 1) {
            $roles[] = EntityInterface::ROLE_EMPLOYEE;
        }

        if ($this->seller == 1) {
            $roles[] = EntityInterface::ROLE_SELLER;
        }
        
        return $roles;
    }

    /**
     * Entity address
     *
     * @param int|null $entityId
     * @return Model
     */
    public function address(?int $entityId = null): object
    {
        $addresses = new EntityAddress();
        $addresses->entity_id = $entityId ?? $this->id;

        return $addresses;
    }

    /**
     * Return true if entity exist
     *
     * @param string $name
     * @return boolean
     */
    public function hasEntity(string $name): bool
    {
        return ($this->where('name','=',$name)->first() !== null);       
    }

    /**
     * Scope query by role
     *
     * @param Builder $query
     * @param string|null $role
     * @param integer|null $userId
     * @param null|string $name
     * @return Builder
     */
    public function scopeQueryByRole($query, ?string $role, ?int $userId = null, ?string $name = null)
    {
        if (empty($name) == false) {
            $query = $query->where('name','=',$name);
        }
        if (empty($role) == false) {
            $query = $query->where($role,'=',1);
        }

        return (empty($userId) == true) ? $query : $query->where('user_id','=',$userId);
    }

    /**
     * Find entity by role
     *
     * @param string  $role
     * @param integer $userId
     * @param string|null $name
     * @return object|null
     */
    public function findEntityByRole(string $role, int $userId, ?string $name = null): ?object
    {
        return $this->queryByRole($role,$userId,$name)->first();
    }

    /**
     * Find or create entity
     *
     * @param string  $name
     * @param string  $type
     * @param integer $userId
     * @param string  $role
     * @return object|null
     */
    public function findOrCreate(string $name, string $type, int $userId, string $role): ?object
    {
        $entity = $this->queryByRole($role,$userId,$name)->first();

        return ($entity !== null) ? $entity : $this->createEntity($name,$type,$userId,$role);
    }

    /**
     * Fiond or create seller entity
     *
     * @param string  $name
     * @param integer $userId
     * @return object|null
     */
    public function findOrCreateSeller(string $name, int $userId): ?object
    {
        return $this->findOrCreate($name,EntityInterface::TYPE_ORGANIZATION,$userId,EntityInterface::ROLE_SELLER);
    }

    /**
     * Create entity
     *
     * @param string $name
     * @param string $type
     * @param integer|null $userId
     * @param string|null $role
     * @return Model|null
     */
    public function createEntity(string $name, string $type, ?int $userId, ?string $role): ?object
    {
        if ($this->hasEntity($name) == true) {
            return null;
        }

        $entityType = $this->crateEntityTypeModel($type);
        if ($entityType == null) {
            return null;
        }
      
        $data = \array_merge([
            'name'          => $name,
            'relation_type' => $type,
            'relation_id'   => $entityType->id,
            'user_id'       => $userId
        ],$this->resolveRole($role));
       
        return $this->create($data);           
    }

    /**
     * Resolve role
     *
     * @param string|null $role
     * @return array
     */
    public function resolveRole(?string $role): array
    {
        $result = [];
        switch($role) {
            case EntityInterface::ROLE_CUSTOMER: {
               $result['customer'] = 1; 
               break;
            }
            case EntityInterface::ROLE_VENDOR: {
                $result['vendor'] = 1; 
                break;
            }
            case EntityInterface::ROLE_SUPPLIER: {
                $result['supplier'] = 1; 
                break;
            }
            case EntityInterface::ROLE_EMPLOYEE: {
                $result['employee'] = 1; 
                break;
            }
            case EntityInterface::ROLE_SELLER: {
                $result['seller'] = 1; 
                break;
            }           
        }

        return $result;
    }

    /**
     * Create entity model type
     *
     * @param string $type
     * @return object|null
     */
    public function crateEntityTypeModel(string $type): ?object
    {
        $model = $this->getEntityTypeModel($type);

        return ($model == null) ? null : $model->create([]);
    }

    /**
     * Get entity type model
     *
     * @param string $type
     * @param integer|null $id
     * @return Model|null
     */
    public function getEntityTypeModel(string $type, ?int $id = null): ?object
    {
        switch ($type) {
            case EntityInterface::TYPE_PERSON:
                $model = new Person();
                break;
            case EntityInterface::TYPE_ORGANIZATION:
                $model = new Organization();
                break;
            default:
                return null;
        }
        
        return (empty($id) == false) ? $model->findById($id) : $model;
    }

    /**
     * Get type relation
     *
     * @return Relation
     */
    public function entityType()
    {
        return $this->morphTo('relation');      
    }
}
