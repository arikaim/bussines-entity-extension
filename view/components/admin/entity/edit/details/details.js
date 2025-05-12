'use strict';

arikaim.component.onLoaded(function() {
    arikaim.ui.form.onSubmit("#entity_form",function() {  
        return entityApi.update('#entity_form');
    },function(result) {       
        arikaim.ui.form.showMessage(result.message);

        arikaim.events.emit('entity.update',result.uuid);
    });   
});