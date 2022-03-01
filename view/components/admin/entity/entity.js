/**
 *  Arikaim
 *  @copyright  Copyright (c) Konstantin Atanasov <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
 */
'use strict';

function EntityControlPanel() {

    this.delete = function(uuid, onSuccess, onError) {
        return arikaim.delete('/api/admin/entity/delete/' + uuid,onSuccess,onError);      
    };

    this.add = function(formId, onSuccess, onError) {
        return arikaim.post('/api/admin/entity/add',formId,onSuccess,onError); 
    };

    this.update = function(formId, onSuccess, onError) {
        return arikaim.put('/api/admin/entity/update',formId,onSuccess,onError); 
    };

    this.setDefault = function(uuid, onSuccess, onError) {           
        var data = { 
            uuid: uuid            
        };

        return arikaim.put('/api/admin/entity/default',data,onSuccess,onError);      
    };

    this.setStatus = function(uuid, status, onSuccess, onError) {           
        var data = { 
            uuid: uuid, 
            status: status 
        };

        return arikaim.put('/api/admin/entity/status',data,onSuccess,onError);      
    };
}

var entity = new EntityControlPanel();

arikaim.component.onLoaded(function() {
    arikaim.ui.tab('.entity-tab-item','entity_content');
});