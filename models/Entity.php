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

use Arikaim\Core\Db\Model as DbModel;
use Arikaim\Extensions\Entity\Classes\EntityInterface;
use Arikaim\Extensions\Entity\Models\Customers;
use Arikaim\Core\Db\Traits\Uuid;
use Arikaim\Core\Db\Traits\Find;
use Arikaim\Core\Db\Traits\Status;
use Arikaim\Core\Db\Traits\DateCreated;
use Arikaim\Core\Db\Traits\SoftDelete;

/**
 * Entity model class
 */
class Entity extends Model  
{
    use Uuid,    
        Status,   
        DateCreated,
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
        'date_created',
        'date_deleted'       
    ];
    
    /**
     * Disable timestamps
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Get type relation
     *
     * @return Relation
     */
    public function enityType()
    {
        return $this->morphTo('relation');      
    }

    /**
     * Get customer relation
     *
     * @return Relation|null
     */
    public function customer()
    {
        return $this->hasOne(Customers::class,'entity_id');
    }

    /**
     * Create entity
     *
     * @param string $name
     * @param string $type
     * @param integer|null $userId
     * @return Model|false
     */
    public function createEntity(string $name, string $type, ?int $userId)
    {
        $entityType = $this->crateEntityTypeModel($type);
        if (\is_object($entityType) == false) {
            return false;
        }

        return $this->create([
            'name'          => $name,
            'relation_type' => $type,
            'relation_id'   => $entityType->id,
            'user_id'       => $userId
        ]);
    }

    /**
     * Add role relation
     *
     * @param integer $entityId
     * @param string $role
     * @return void
     */
    public function addRoleRelation(int $entityId, string $role)
    {
        switch ($role) {
            case EntityInterface::ROLE_CUSTOMER:
                $model = DbModel::Customers('entity');
                break;
            default:
                return false;
        }

        $created = $model->create(['entity_id' => $entityId]);

        return (\is_object($created) == true) ? $created : false;
    }

    /**
     * Create entity model type
     *
     * @param string $type
     * @return object|false
     */
    public function crateEntityTypeModel(string $type)
    {
        switch ($type) {
            case EntityInterface::TYPE_PERSON:
                $model = DbModel::Person('entity');
                break;
            case EntityInterface::TYPE_ORGANIZATION:
                $model = DbModel::Organization('entity');
                break;
            default:
                return false;
        }

        $created = $model->create([]);

        return (\is_object($created) == true) ? $created : false;
    }
}
