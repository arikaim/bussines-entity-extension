'use strict';

arikaim.component.onLoaded(function() {
    arikaim.ui.button('.close-button',function() {
        $('.new-entity-panel').html('').parent().hide();
    });
    
    arikaim.ui.form.onSubmit("#entity_form",function() {  
        return entity.update('#entity_form');
    },function(result) {       
        arikaim.ui.form.showMessage(result.message);
    });   
});