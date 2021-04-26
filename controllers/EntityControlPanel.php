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

/**
 * Enity control panel api controler
*/
class EntityControlPanel extends ControlPanelApiController
{
    use Status;

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
        $this->onDataValid(function($data) {
            $type = $data->get('type','person');
            $name = $data->get('name',null);
            $role = $data->get('role',null);
            $userId = $this->getUserId();

            $entity = Model::Entity('entity');
            $model = $entity->findByColumn($name,'name');
            if (\is_object($model) == true) {
                $this->error('errors.exist');
                return;
            }

            $newEntity = $entity->createEntity($name,$type,$userId);
            if (\is_object($newEntity) == true && empty($role) == false) {
                $newEntity->addRoleRelation($newEntity->id,$role);
            }

            $this->setResponse(\is_object($newEntity),function() use($newEntity) {            
                $this
                    ->message('add')
                    ->field('uuid',$newEntity->uuid);  
            },'errors.add');
        }); 
        $data
            ->addRule('text:min=2|required','name')
            ->validate();

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
        $this->onDataValid(function($data) {
            $currency = Model::Currency('currency');
            $data['crypto'] = $data->get('crypto',0);
            
            $model = $currency->where('code','=',$data['code'])->where('uuid','<>',$data['uuid'])->first();
            if (\is_object($model) == true) {
                $this->error('errors.exist');
                return;
            }

            $currency = $currency->findById($data['uuid']);
            $result = $currency->update($data->toArray());

            $this->setResponse($result,function() use($currency) {            
                $this
                    ->message('update')
                    ->field('uuid',$currency->uuid);  
            },'errors.update');
        }); 
        $data->validate();
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
        $this->onDataValid(function($data) {
            $currency = Model::Currency('currency')->findById($data['uuid']);
            $result = $currency->delete();

            $this->setResponse($result,function() use($currency) {            
                $this
                    ->message('delete')
                    ->field('uuid',$currency->uuid);  
            },'errors.delete');
        }); 
        $data->validate();
    }
}
