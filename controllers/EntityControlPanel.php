<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Entity\Controllers;

use Arikaim\Core\Db\Model;
use Arikaim\Core\Controllers\ControlPanelApiController;

use Arikaim\Core\Controllers\Traits\Status;
use Arikaim\Core\Controllers\Traits\SoftDelete;

/**
 * Entity control panel api controler
*/
class EntityControlPanel extends ControlPanelApiController
{
    use 
        Status,
        SoftDelete;

    /**
     * Init controller
     *
     * @return void
     */
    public function init()
    {
        $this->loadMessages('entity::admin.messages');
    }

    /**
     * Constructor
     *
     * @param Container|null $container
     */
    public function __construct($container = null)
    {
        parent::__construct($container);
        $this->setExtensionName('entity');
        $this->setModelClass('Entity');
    }

    /**
     * Add entity
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function addController($request, $response, $data) 
    {        
        $data
            ->addRule('text:min=2|required','name')
            ->validate(true);

        $type = $data->get('relation_type','person');
        $name = $data->get('name',null);
        $role = $data->get('role',null);
        $userId = $this->getUserId();

        $entity = Model::Entity('entity');
        $model = $entity->createEntity($name,$type,$userId,$role);

        $this->setResponse(\is_object($model),function() use($model, $role, $type) {            
            $this
                ->message('add')
                ->field('uuid',$model->uuid)
                ->field('type',$type)
                ->field('role',$role);  
        },'errors.add'); 
    }

    /**
     * Update entity
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function updateController($request, $response, $data) 
    {        
        $data->validate(true);

        $uuid = $data->get('uuid',null);
        $entity = Model::Entity('entity')->findById($uuid);
                
        if ($entity == null) {
            $this->error('errors.id','Not valid entity id');
            return;
        }
        
        $result = $entity->update($data->toArray());

        $this->setResponse($result,function() use($entity) {            
            $this
                ->message('update')
                ->field('uuid',$entity->uuid);  
        },'errors.update');
    }

    /**
     * Delete entity
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function deleteController($request, $response, $data) 
    {        
        $data->validate(true);

        $uuid = $data->get('uuid',null);
        $entity = Model::Entity('entity')->findById($uuid);
                
        if ($entity == null) {
            $this->error('errors.id','Not valid entity id');
            return false;
        }
        
        $result = $entity->delete();

        $this->setResponse(($result !== false),function() use($entity) {            
            $this
                ->message('delete')
                ->field('uuid',$entity->uuid);  
        },'errors.delete');
    }
}
