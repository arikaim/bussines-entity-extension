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
 * Entity db table
 */
class EntitySchema extends Schema  
{    
    /**
     * Table name
     *
     * @var string
     */
    protected $tableName = 'entity';

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
        $table->status();
        $table->userId();
        $table->string('name')->nullable(false);
        $table->string('relation_type')->nullable(false);
        $table->integer('relation_id')->nullable(false);    
        $table->boolean('customer')->nullable(true);    
        $table->boolean('supplier')->nullable(true);       
        $table->boolean('vendor')->nullable(true);    
        $table->boolean('employee')->nullable(true);         
        $table->integer('owned_by_user')->nullable(true);    

        $table->dateCreated();
        $table->dateUpdated();
        $table->dateDeleted();
        // index     
        $table->index(['relation_type','relation_id']);
        $table->unique(['name','user_id']);          
        $table->unique(['relation_type','relation_id','user_id'],'un_rel_id_type_' . $table->getTable());   
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
