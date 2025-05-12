'use strict';

arikaim.component.onLoaded(function() { 
    arikaim.ui.form.onSubmit("#entity_form",function() {  
        return entityApi.add('#entity_form');
    },function(result) {     
        arikaim.page.toastMessage(result.message);

        arikaim.events.emit('entity.create',result.uuid);

        arikaim.page.loadContent({
            id: 'details_content',
            component: 'entity::admin.entity.edit',
            params: {              
                uuid: result.uuid 
            }
        });
    });
});