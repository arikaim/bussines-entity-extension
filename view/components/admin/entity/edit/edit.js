'use strict';

arikaim.component.onLoaded(function() { 

    arikaim.ui.subscribe('select_entity','selected',function(value) {
        arikaim.page.loadContent({
            id: 'edit_entity_content',
            component: 'entity::admin.entity.edit.tabs',
            params: { uuid: value }
        });
    });

});