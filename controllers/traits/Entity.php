<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Intersoft Ltd <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Entity\Controllers\Traits;

use Arikaim\Core\Db\Model;
use Arikaim\Core\Validator\Validator;

/**
 * Entity trait
*/
trait Entity 
{
    /**
     * Add entity
     *    
     * @param Validator $data
     * @return object|null
    */
    public function addEntity(Validator $data): ?object
    {       
        $type = $data->get('type','person');
        $name = $data->get('name',null);
        $role = $data->get('role','customer');
        $userId = (int)$data->get('user',$this->getUserId());
      
        $entity = Model::Entity('entity')->createEntity($name,$type,$userId,$role);      
        if ($entity == null) {
            $this->error('errors.add','Error create entity');
            return null;
        }
            
        return $entity;
    }

    /**
     * Find or create entity
     * @param Validator $data
     * @return mixed|object|null
     */
    public function findOrCreateEntity(Validator $data): ?object
    { 
        $name = $data->get('name',null);
        $userId = (int)$data->get('user',$this->getUserId());
        
        $entity = Model::Entity('entity')->findEntity($name,$userId);

        return ($entity == null) ? $this->addEntity($data) : $entity;          
    }
}
