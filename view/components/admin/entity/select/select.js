'use strict';

arikaim.component.onLoaded(function() {  
    arikaim.ui.button('.new-entity-button',function(element) {
        var contentId = $(element).attr('content-id');
        var role = $(element).attr('role');
      
        arikaim.ui.loadComponent({
            mountTo: contentId,
            params: {
                role: role,
            },           
            name: 'entity::admin.entity.add'
        })
    });

    arikaim.ui.button('.edit-entity-button',function(element) {
        var contentId = $(element).attr('content-id');
        var dropdownId = $(element).attr('dropdown-id');
        var uuid = arikaim.ui.getComponent(dropdownId).get('selected');

        if (isEmpty(uuid) == false) {
            arikaim.ui.loadComponent({
                mountTo: contentId,
                params: {
                    uuid: uuid
                },           
                name: 'entity::admin.entity.edit'
            });
        }
    });
});