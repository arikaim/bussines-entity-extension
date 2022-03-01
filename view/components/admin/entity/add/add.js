'use strict';

arikaim.component.onLoaded(function() { 
    arikaim.ui.form.onSubmit("#entity_form",function() {  
        return entity.add('#entity_form');
    },function(result) {
        arikaim.ui.form.clear('#entity_form');
        arikaim.ui.form.showMessage(result.message);

        arikaim.page.loadContent({
            id: 'entity_content',
            component: 'entity::admin.entity.edit',
            params: { uuid: result.uuid }
        });
    });
});