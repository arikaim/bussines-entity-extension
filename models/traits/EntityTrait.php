<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Entity\Models\Traits;

use Arikaim\Extensions\Entity\Classes\EntityInterface;
use Arikaim\Extensions\Entity\Models\Organization;
use Arikaim\Extensions\Entity\Models\Person;

/**
 * Entity trait
*/
trait EntityTrait 
{    
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

        $model = $this->create([
            'name'          => $name,
            'relation_type' => $type,
            'relation_id'   => $entityType->id,
            'user_id'       => $userId
        ]);

        if (\is_object($model) == false) {
            return false;
        }

        if (empty($role) == false) {
            $this->addRoleRelation($model->id,$role);
        }

        return $model;
    }

    /**
     * Update entity
     *
     * @param string $key
     * @param array $data
     * @return boolean
     */
    public function updatEntity(string $key, array $data): bool
    {
        $model = $this->findByColumn($key,'name'); 
        $model = \is_object($model) ? $model : $this->findById($key);
        if (\is_object($model) == false) {
            return false;
        }

        $model->update($data);
        $result = $model->entityTyep()->update($data);

        return ($result !== false);
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
                $model = new Person();
                break;
            case EntityInterface::TYPE_ORGANIZATION:
                $model = new Organization();
                break;
            default:
                return false;
        }

        $created = $model->create([]);

        return (\is_object($created) == true) ? $created : false;
    }
}
