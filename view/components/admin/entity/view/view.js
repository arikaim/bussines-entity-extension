/**
 *  Arikaim
 *  @copyright  Copyright (c) Konstantin Atanasov <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
 */
'use strict';

function EntityView() {
    var self = this;
    
    this.initRows = function() {
        arikaim.ui.button('.edit-entity',function(element) {
            var uuid = $(element).attr('uuid');

            arikaim.page.loadContent({
                id: 'entity_content',
                component: 'entity::admin.entity.edit',
                params: { uuid: uuid }
            }); 
        });
        
        $('.status-dropdown').dropdown({
            onChange: function(value) {
                var uuid = $(this).attr('uuid');
                entity.setStatus(uuid,value);               
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
                currency.delete(uuid,function(result) {
                    $('#' + uuid).remove();                
                });
            });
        });
    };

    this.init = function() {
        this.loadMessages('entity::admin.customers');
        
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
    };
}

var entityView = new createObject(EntityView,ControlPanelView);

arikaim.component.onLoaded(function() {
    entityView.init();
    entityView.initRows();
});