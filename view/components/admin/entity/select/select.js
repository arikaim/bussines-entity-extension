'use strict';

arikaim.component.onLoaded(function(component) { 
    
    component.init = function() {
        arikaim.ui.button('.new-entity-button',function(element) {
            arikaim.ui.loadComponent({
                mountTo: component.get('content-id'),
                params: {
                    role: component.get('role'),
                },           
                name: 'entity::admin.entity.add'
            })
        });

        arikaim.ui.button('.edit-entity-button',function(element) {
            var select = arikaim.ui.getComponent(component.get('dropdown-id'))      
            var uuid = select.getValue();

            arikaim.events.on('entity.update',function() {
                select.reinit();
            },'entityUpdateSelect');

                if (isEmpty(uuid) == false) {
                    arikaim.ui.loadComponent({
                        mountTo: component.get('content-id'),
                        params: {
                            uuid: uuid
                        },           
                        name: 'entity::admin.entity.edit'
                    });
                }
        });
    }
    
    component.init();

    return component;
});