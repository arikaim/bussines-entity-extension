'use strict';

arikaim.component.onLoaded(function(component) {
   
    component.init = function() { 
        var dataField = component.get('data-field').trim();
        var role = component.get('role').trim();
        var user = component.get('user').trim();

        $('#' + component.getId()).dropdown({
            apiSettings: {     
                on: 'now',      
                url: arikaim.getBaseUrl() + '/api/entity/list/' + dataField + '/' + user + '/' + role + '/{query}',   
                cache: false        
            },       
            onChange: function(value) {                
                component.set('selected',value);
            },
            filterRemoteData: false                
        }); 
    }
    
    component.init();

    return component;
});