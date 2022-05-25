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

use Arikaim\Core\Content\Type\Action;
use Arikaim\Core\Db\Model;
use Arikaim\Extensions\Entity\Classes\EntityInterface;

/**
 * Address import form stripe checkout transaction data
 */
class ImportFromStripeCheckout extends Action
{
    /**
     * Init action
     *
     * @return void
     */
    public function init(): void
    {
        $this->setName('entity.import.stripe');
        $this->setType('import');
        $this->setTitle('Import customer from stripe transaction data.');
    }

    /**
     * Execute action
     *
     * @param mixed $content    
     * @param array|null $options
     * @return mixed
     */
    public function execute($content, ?array $options = []) 
    {
        $name = $content['customer_details']['name'] ?? null;
    
        $type = $content['type'] ?? EntityInterface::TYPE_PERSON;
        $role = $content['role'] ?? EntityInterface::ROLE_CUSTOMER;
        $userId = $content['user_id'] ?? null;
        $userId = (empty($userId) == true) ? null : (int)$userId;
        $addressId = $content['address_id'] ?? null;
        $addressId = (empty($addressId) == true) ? null : (int)$addressId;
        
        $entity = Model::Entity('entity')->createEntity($name,$type,$userId,$role);
        if (\is_object($entity) == true && empty($addressId) == false) {
            // add address relation
            $entity->address()->linkAddress('home',$addressId);
        }
        
        return $entity;
    }
}
