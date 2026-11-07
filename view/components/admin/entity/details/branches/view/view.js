'use strict';

function EntityView() {
    var self = this;
    
    this.initRows = function() {
        arikaim.ui.loadComponentButton('.entity-action');

        $('.status-dropdown').on('change', function() {
            var value = $().val();
            var uuid = $(this).attr('uuid');

            entityApi.setStatus(uuid,value);               
        });

        arikaim.ui.button('.delete-entity',function(element) {
            var uuid = $(element).attr('uuid');
            var title = $(element).attr('data-title');
            var message = arikaim.ui.template.render(self.getMessage('remove.content'),{ title: title });

            arikaim.ui.getComponent('confirm_delete').open(function() {
                entityApi.delete(uuid,function(result) {                   
                    $('#row_' + uuid).remove(); 
                    arikaim.ui.getComponent('toast').show(result.message);                    
                });
            },message);          
        });     
    };

    this.init = function() {
        this.loadMessages('entity::admin.messages');
        
        arikaim.ui.loadComponentButton('.add-entity');
        
        arikaim.events.on('entity.create',function(uuid) {
            $('#empty_row').remove();
            
            arikaim.ui.loadComponent({
                mountTo: 'items_list',
                prepend: true,
                params: {
                    uuid: uuid
                },
                component: 'entity::admin.entity.view.item'
            },function() {
                self.initRows();
            }) 
        },'onEntityCreate');

        arikaim.events.on('entity.update',function(uuid) {
            arikaim.ui.loadComponent({
                mountTo: 'row_' + uuid ,
                replace: true,
                params: {
                    uuid: uuid
                },
                component: 'entity::admin.entity.view.item'
            },function() {
                self.initRows();
            });
        },'onEntityUpdate');

        var role = $('#items_list').attr('role').trim();
        var namespace = 'entity.' + role;

        search.init({
            id: 'items_list',
            component: 'entity::admin.entity.view.rows',
            event: 'entity.search.load'
        },namespace);
        
        arikaim.events.on('entity.search.load',function(result) {      
            arikaim.ui.getComponent('entity_paginator').reload(); 
            self.initRows();    
        },'entitySearch');   

        this.initRows();
    };
}

var entityView = new createObject(EntityView,ControlPanelView);

arikaim.component.onLoaded(function() {
    entityView.init();  
});