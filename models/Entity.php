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
use Arikaim\Extensions\Entity\Classes\EntityInterface;

/**
 * Entity model class
 */
class Entity extends Model  
{
    use Uuid,    
        Status,   
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
        'owned_by_user',
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
        if (\is_object($type) == true) {
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
     * Entity address
     *
     * @param int|null $entityId
     * @return Model
     */
    public function address(?int $entityId = null)
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
        $model = $this->where('name','=',$name)->first();

        return \is_object($model);
    }

    /**
     * Scope query by role
     *
     * @param Builder $query
     * @param string|null $role
     * @param integer|null $userId
     * @return Builder
     */
    public function scopeQueryByRole($query, ?string $role, ?int $userId = null)
    {
        if ($role == EntityInterface::ROLE_OWNER) {
            return $query->where('owned_by_user','=',$userId);
        } 

        return $query->where($role,'=',1);
    }

    /**
     * Create entity
     *
     * @param string $name
     * @param string $type
     * @param integer|null $userId
     * @param string|null $role
     * @return Model|false
     */
    public function createEntity(string $name, string $type, ?int $userId, ?string $role)
    {
        if ($this->hasEntity($name) == true) {
            return false;
        }

        $entityType = $this->crateEntityTypeModel($type);
        if (\is_object($entityType) == false) {
            return false;
        }

        $data = [
            'name'          => $name,
            'relation_type' => $type,
            'relation_id'   => $entityType->id,
            'user_id'       => $userId
        ];
        $roleData = $this->resolveRole($role,$userId);
        $data = \array_merge($data,$roleData);
       
        $model = $this->create($data);
        
        return $model;       
    }

    /**
     * Resolve role
     *
     * @param string|null $role
     * @param integer|null $userId
     * @return array
     */
    public function resolveRole(?string $role, ?int $userId = null): array
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
            case EntityInterface::ROLE_OWNER: {
                $result['owned_by_user'] = $userId; 
                break;
            }           
        }

        return $result;
    }

    /**
     * Create entity model type
     *
     * @param string $type
     * @return object|false
     */
    public function crateEntityTypeModel(string $type)
    {
        $model = $this->getEntityTypeModel($type);
        $created = $model->create([]);

        return (\is_object($created) == true) ? $created : false;
    }

    /**
     * Get entity type model
     *
     * @param string $type
     * @param integer|null $id
     * @return Model|null
     */
    public function getEntityTypeModel(string $type, ?int $id = null) 
    {
        switch ($type) {
            case EntityInterface::TYPE_PERSON:
                $model = new Person();
                break;
            case EntityInterface::TYPE_ORGANIZATION:
                $model = new Organization();
                break;
            default:
                return false;
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
