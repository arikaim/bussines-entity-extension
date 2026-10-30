<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c) Intersoft Ltd <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Entity\Models\Traits;

use Arikaim\Extensions\Entity\Models\Entity;

/**
 * Entity relation trait
*/
trait EntityRelation 
{    
    /**
     * Entity relation
     *
     * @return mixed
     */
    public function entity() 
    {
        return $this->belongsTo(Entity::class,$this->getEntityIdAttributeName());
    }    

    /**
     * Link entity
     * @param int $entityId
     * @return bool
     */
    public function linkEntity(int $entityId): bool
    {       
        $result = $this->update([
            $this->getEntityIdAttributeName() => $entityId
        ]);

        return ($result !== false);
    }

    /**
     * Find entity
     *
     * @param string $name
     * @return object|null
     */
    public function findEntity(string $name): ?object
    {
        return (empty($code) == true) ? $this->entity : $this->entity->findEntity($name);
    }

    /**
     * Get entity id attribute name
     *
     * @return string
     */
    public function getEntityIdAttributeName()
    {
        return (isset($this->entityIdColumn) == true) ? $this->entityIdColumn : 'entity_id';
    }
}
