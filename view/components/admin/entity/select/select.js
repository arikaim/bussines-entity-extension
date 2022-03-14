'use strict';

arikaim.component.onLoaded(function() {  
    arikaim.ui.button('.new-entity-button',function(element) {
        var contentId = $(element).attr('content-id');
        var role = $(element).attr('role');
        $('#' + contentId).show();

        arikaim.ui.loadComponent({
            mountTo: contentId,
            params: {
                role: role,
            },           
            name: 'entity::admin.entity.panel'
        })
    });
});