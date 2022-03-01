<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Entity\Models\Schema;

use Arikaim\Core\Db\Schema;

/**
 * Entity addreds relations db table
 */
class EntityAddressSchema extends Schema  
{    
    /**
     * Table name
     *
     * @var string
     */
    protected $tableName = 'entity_address';

    /**
     * Create table
     *
     * @param \Arikaim\Core\Db\TableBlueprint $table
     * @return void
     */
    public function create($table) 
    {            
        $table->id();
        $table->prototype('uuid');   
        $table->relation('entity_id','entity');
        $table->relation('address_id','address');
        $table->string('address_type')->nullable(false);
        // unique            
        $table->unique(['entity_id','address_type']);                 
    }

    /**
     * Update table
     *
     * @param \Arikaim\Core\Db\TableBlueprint $table
     * @return void
     */
    public function update($table) 
    {              
    }
}
