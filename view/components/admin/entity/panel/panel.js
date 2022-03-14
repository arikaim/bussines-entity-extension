'use strict';

arikaim.component.onLoaded(function() {  
    arikaim.ui.button('.close-button',function() {
        $('.new-entity-panel').html('').parent().hide();
    });

    arikaim.ui.form.onSubmit("#entity_form",function() {  
        return entity.add('#entity_form');
    },function(result) {
        arikaim.ui.form.clear('#entity_form');
    
        arikaim.page.loadContent({
            id: 'new_entity_panel',
            component: 'entity::admin.entity.panel.edit',
            params: { 
                uuid: result.uuid,
                role: result.role
            }
        });
    });
});