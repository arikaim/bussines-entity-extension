<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Entity\Service;

use Arikaim\Core\Db\Model;
use Arikaim\Core\Service\Service;
use Arikaim\Core\Service\ServiceInterface;

/**
 * Entity service class
*/
class EntityService extends Service implements ServiceInterface
{
    /**
     * Boot service
     *
     * @return void
     */
    public function boot()
    {
        $this->setServiceName('entity');
    }

    /**
     * Find or create seller
     *
     * @param string $name
     * @return object|null
     */
    public function findOrCreateSeller(string $name, int $userId): ?object
    {
        return Model::Entity('entity')->findOrCreateSeller($name,$userId);
    }
}
