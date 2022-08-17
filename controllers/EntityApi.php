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

/**
 * Entity api controller
*/
class EntityApi extends ApiController
{
    /**
     * Init controller
     *
     * @return void
     */
    public function init()
    {
        $this->loadMessages('entity::messages');
    }

    /**
     * Add entity
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function add($request, $response, $data) 
    {       
        $data
            ->addRule('text:min=2|required','name')
            ->validate(true); 

        $type = $data->get('relation_type','person');
        $name = $data->get('name',null);
        $role = $data->get('role',null);
        $userId = $this->getUserId();

        $entity = Model::Entity('entity')->createEntity($name,$type,$userId,$role);      
        if ($entity == null) {
            $this->error('errors.add','Error create entity');
            return false;
        }
                 
        $this
            ->message('add','Added successfully.')
            ->field('uuid',$entity->uuid)
            ->field('type',$type)
            ->field('role',$role);  
    }

    /**
     * Update entity
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
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
        // check access
        $this->requireUser($entity->user_id);

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
     * @return Psr\Http\Message\ResponseInterface
    */
    public function getList($request, $response, $data) 
    {       
        $data->validate(true);

        $search = $data->get('query','');
        $role = $data->get('role','all');            
        $size = $data->get('size',5);
        $dataField = $data->get('data_field','uuid');     

        $model = Model::Entity('entity')->getActive();
        if ($role != 'all' && empty($role) == false) {
            $model = $model->queryByRole($role);
        }
        $model = $model->where('name','like',"%$search%")->take($size)->get();

        $this->setResponse(\is_object($model),function() use($model,$dataField) {     
            $items = [];
            foreach ($model as $item) {
                $items[] = [
                    'name' => $item['name'],
                    'value' => $item[$dataField]
                ];
            }
            $this                    
                ->field('success',true)
                ->field('results',$items);  
        },'errors.list');                                
      
        return $this->getResponse(true);
    }
}
