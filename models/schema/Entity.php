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
class Entity extends Schema  
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
        $table->string('type')->nullable(false);
        $table->boolean('customer')->nullable(true);    
        $table->boolean('supplier')->nullable(true);       
        $table->boolean('vendor')->nullable(true);    
        $table->boolean('employee')->nullable(true);       
        $table->boolean('seller')->nullable(true);         
        $table->integer('age')->nullable(true);
        $table->text('note')->nullable(true);         

        $table->dateCreated();
        $table->dateUpdated();
        $table->dateDeleted();
        // index     
        $table->index(['type','user_id']);
        $table->unique(['name','user_id']);          
    }

    /**
     * Update table
     *
     * @param \Arikaim\Core\Db\TableBlueprint $table
     * @return void
     */
    public function update($table) 
    {       
        if ($this->hasColumn('note') == false) {
            $table->text('note')->nullable(true);    
        } 
        
        if ($this->hasColumn('age') == false) {
            $table->integer('age')->nullable(true);
        } 
    }
}
