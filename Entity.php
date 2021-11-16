<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Entity;

use Arikaim\Core\Extension\Extension;

/**
 * Entity extension
*/
class Entity extends Extension
{
    /**
     * Install extension routes, events, jobs ..
     *
     * @return void
    */
    public function install()
    {        
        // Control Panel
        $this->addApiRoute('POST','/api/admin/entity/add','EntityControlPanel','add','session');   
        $this->addApiRoute('PUT','/api/admin/entity/update','EntityControlPanel','update','session'); 
        $this->addApiRoute('DELETE','/api/admin/entity/delete/{uuid}','EntityControlPanel','delete','session');     
        $this->addApiRoute('PUT','/api/admin/entity/status','EntityControlPanel','setStatus','session');        
        // Api Routes
        $this->addApiRoute('GET','/api/entity/list/{language}','EntityApi','getList');     
        
        // Create db tables
        $this->createDbTable('EntitySchema');     
        $this->createDbTable('PersonSchema');   
        $this->createDbTable('OrganizationSchema');  
        $this->createDbTable('EntityAddressSchema');       
        
        // Content Types
        $this->registerContentType('Classes\\EntityContentType');

        // Relations map 
        $this->addRelationsMap([
            'person'       => 'Person',
            'organization' => 'Organization'
        ]);
    } 
    
    /**
     * UnInstall extension
     *
     * @return void
     */
    public function unInstall()
    {  
    }
}
