'use strict';

function EntityView() {
    var self = this;
    
    this.initRows = function() {
        arikaim.ui.loadComponentButton('.entity-action');

        arikaim.ui.button('.edit-entity',function(element) {
            arikaim.page.loadContent({
                id: 'details_content',
                component: 'entity::admin.entity.edit',
                params: { uuid: $(element).attr('uuid') }
            }); 
        });
        
        arikaim.ui.button('.entity-details',function(element) {
            var uuid = $(element).attr('uuid');

            arikaim.page.loadContent({
                id: 'details_content',
                component: 'entity::admin.entity.details',
                params: { uuid: uuid }
            }); 
        });

        $('.status-dropdown').dropdown({
            onChange: function(value) {
                var uuid = $(this).attr('uuid');
                entityApi.setStatus(uuid,value);               
            }
        });

        arikaim.ui.button('.delete-entity',function(element) {
            var uuid = $(element).attr('uuid');
            var title = $(element).attr('data-title');

            var message = arikaim.ui.template.render(self.getMessage('remove.content'),{ title: title });

            modal.confirmDelete({ 
                title: self.getMessage('remove.title'),
                description: message
            },function() {
                entityApi.delete(uuid,function(result) {                   
                    $('#row_' + uuid).remove();                
                });
            });
        });     
    };

    this.init = function() {
        this.loadMessages('entity::admin.messages');
        
        arikaim.ui.loadComponentButton('.add-entity');
        
        arikaim.events.on('entity.create',function(uuid) {
            $('#empty_row').remove();
            
            arikaim.ui.loadComponent({
                mountTo: 'items_list',
                append: true,
                params: {
                    uuid: uuid
                },
                component: 'entity::admin.entity.view.item'
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
            }) 
        },'onEntityUpdate');

        var role = $('#items_list').attr('role').trim();
        var namespace = 'entity.' + role;

        paginator.init('items_list',{
            name: 'entity::admin.entity.view.rows',
            params: {
                namespace: namespace
            }
        }); 

        search.init({
            id: 'items_list',
            component: 'entity::admin.entity.view.rows',
            event: 'entity.search.load'
        },namespace);
        
        arikaim.events.on('entity.search.load',function(result) {      
            paginator.reload();
            self.initRows();    
        },'entitySearch');   

        this.initRows();
    };
}

var entityView = new createObject(EntityView,ControlPanelView);

arikaim.component.onLoaded(function() {
    entityView.init();  
});