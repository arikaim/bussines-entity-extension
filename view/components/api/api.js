'use strict';

function EntityApi() {
    
    this.add = function(formId, onSuccess, onError) {
        return arikaim.post('/api/entity/add',formId,onSuccess,onError);          
    };

    this.update = function(formId, onSuccess, onError) {
        return arikaim.put('/api/entity/update',formId,onSuccess,onError);          
    };

    this.delete = function(uuid, onSuccess, onError) {
        return arikaim.delete('/api/entity/delete/' + uuid,onSuccess,onError);          
    };

    this.setStatus = function(uuid, status, onSuccess, onError) {           
        var data = { 
            uuid: uuid, 
            status: status 
        };

        return arikaim.put('/api/entity/status',data,onSuccess,onError);      
    };
}

var entityApi = new EntityApi();