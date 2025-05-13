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
 * Person db table
 */
class Person extends Schema  
{    
    /**
     * Table name
     *
     * @var string
     */
    protected $tableName = 'person';

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
        $table->userId();       
        $table->integer('age')->nullable(true);
        $table->text('note')->nullable(true);         
        $table->dateCreated();
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
