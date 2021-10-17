<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Entity\Classes;

use Arikaim\Core\Content\Type\ContentType;

/**
 * Entity content type class
*/
class EntityContentType extends ContentType 
{
    /**
     * Define customer type
     *
     * @return void
     */
    protected function define(): void
    {
        $this->setName('entity');
        $this->setTitle('Entity');
        // fields
        $this->addField('name','text','Name');       
        $this->addField('type','text','Type'); // person or organization
        $this->addField('role','text','Type');
        // address 
        $this->addField('city','text','City');
        $this->addField('country','text','Country');
        $this->addField('state','text','State');
        $this->addField('zip_code','number','Zip Code');
        $this->addField('address','text','Address');
        $this->addField('address_2','text','Address 2');
        $this->addField('email','email','Email');
        $this->addField('phone','number','Phone');
        $this->addField('website','url','Website');
        // searchable fields
        $this->setSearchableFields([
            'name',
            'zip_code',
            'phone',
            'email'
        ]);

        $this->setTitleFields([
            'name',       
            'email'
        ]);
    }
}
