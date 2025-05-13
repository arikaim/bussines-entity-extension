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
use Arikaim\Core\Controllers\ApiController;

use Arikaim\Core\Controllers\Traits\Status;
use Arikaim\Core\Controllers\Traits\SoftDelete;

/**
 * Entity api controller
*/
class EntityApi extends ApiController
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
        $this->loadMessages('entity::messages');
        $this->setExtensionName('entity');
        $this->setModelClass('Entity');

        $this->onBeforeStatusUpdate(function ($status,$model) {
            $this->requireUserOrControlPanel($model->user_id);
        });
    }

    /**
     * Delete entity
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return mixed
    */
    public function delete($request, $response, $data) 
    {       
        $data
            ->validate(true);

        $uuid = $data->get('uuid',null);
        $entity = Model::Entity('entity')->findById($uuid);
                
        if ($entity == null) {
            $this->error('errors.id','Not valid entity id');
            return false;
        }
        
        // check access
        $this->requireUserOrControlPanel($entity->user_id);

        $result = $entity->deleteEntity();

        $this->setResponse(($result !== false),function() use($entity) {            
            $this
                ->message('delete')
                ->field('uuid',$entity->uuid);  
        },'errors.delete');
    }

    /**
     * Add entity
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return mixed
    */
    public function add($request, $response, $data) 
    {       
        $data
            ->addRule('text:min=2|required','name')
            ->validate(true); 

        $type = $data->get('type','person');
        $name = $data->get('name',null);
        $role = $data->get('role','customer');
        $userId = (int)$data->get('user',$this->getUserId());
        $userId = ($userId == 0) ? $this->getUserId() : $userId;

        $entity = Model::Entity('entity')->createEntity($name,$type,$userId,$role);      
        if ($entity == null) {
            $this->error('errors.add','Error create entity');
            return false;
        }
                 
        $this
            ->message('add','Added successfully.')
            ->field('uuid',$entity->uuid)
            ->field('type',$type)
            ->field('user',$userId)
            ->field('role',$role);  
    }

    /**
     * Update entity
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return mixed
    */
    public function update($request, $response, $data) 
    {        
        $data->validate(true);

        $uuid = $data->get('uuid',null);
       
        $entity = Model::Entity('entity')->findById($uuid);                   
        if ($entity == null) {
            $this->error('errors.id','Not vlaid entity id');
            return false;
        }

        // Check access
        $this->requireUserOrControlPanel($entity->user_id);

        $result = $entity->update($data->toArray());
        if ($result === false) {
            $this->error('errors.udpate','Error save entity');
            return false;
        }
        
        $this
            ->message('update','Saved successfully.')
            ->field('uuid',$entity->uuid);         
    }

    /**
     * Get Entity list (for Entity dropdown)
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return mixed
    */
    public function getList($request, $response, $data) 
    {       
        $data->validate(true);

        $search = $data->get('query','');
        $role = $data->get('role','all');            
        $size = $data->get('size',5);
        $dataField = $data->get('data_field','uuid');     
        $user = $data->get('user',null);
        $user = ($user == 'null') ? null : $user;
        
        $model = Model::Entity('entity')->getActive();
        if ($role != 'all' && empty($role) == false) {
            $model = $model->queryByRole($role);
        }
        if (empty($user) == false) {
            $model = $model->userQuery($user);
        }
        
        if (empty($search) == false) {
            $model = $model->searchIgnoreCase('name',$search)->get();
        } else {
            $model = $model->take($size)->get();
        }
       
        if ($model == null) {
            $this->error('errors.list','Error create entity list');
        }

        $items = [];
        foreach ($model as $item) {
            $items[] = [
                'name'  => $item['name'],
                'value' => $item[$dataField]
            ];
        }
        $this                    
            ->field('success',true)
            ->field('results',$items);  
                                        
        return $this->getResponse(true);
    }
}
