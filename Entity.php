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
        // Api Routes
        $this->addApiRoute('GET','/api/entity/list/{data_field}/{user}/{role}/[{query}]','EntityApi','getList','session'); 
        $this->addApiRoute('POST','/api/entity/add','EntityApi','add','session');   
        $this->addApiRoute('PUT','/api/entity/update','EntityApi','update','session');     
        $this->addApiRoute('DELETE','/api/entity/delete/{uuid}','EntityApi','delete','session');     
        $this->addApiRoute('PUT','/api/entity/status','EntityApi','setStatus','session');        
        // Content Types
        $this->registerContentType('Classes\\EntityContentType');
        // Content type actions
        $this->registerContentTypeAction('entity','Classes\\ImportFromStripeCheckout');  
        $this->registerContentTypeAction('entity','Classes\\ImportFromPayPalCheckout');  
        // Relations map 
        $this->addRelationsMap([
            'entity' => 'Entity'
        ]);
        // Ssevice
        $this->registerService('EntityService');        
    } 

    public function dbInstall(): void
    {  
        // Create db tables
        $this->createDbTable('Entity');           
        $this->createDbTable('EntityAddress');     
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
