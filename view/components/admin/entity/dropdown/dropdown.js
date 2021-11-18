'use strict';

arikaim.component.onLoaded(function() {
    var dataField = $('.entity-dropdown').attr('data-field');
    var role = $('.entity-dropdown').attr('role');
   
    console.log(role);
    
    $('.entity-dropdown').dropdown({
        apiSettings: {     
            on: 'now',      
            url: arikaim.getBaseUrl() + '/api/entity/list/' + dataField + '/' + role + '/{query}',   
            cache: false        
        },       
        filterRemoteData: false                
    });
});