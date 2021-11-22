'use strict';

arikaim.component.onLoaded(function() {
    var dataField = $('.entity-dropdown').attr('data-field').trim();
    var role = $('.entity-dropdown').attr('role').trim();
     
    $('.entity-dropdown').dropdown({
        apiSettings: {     
            on: 'now',      
            url: arikaim.getBaseUrl() + '/api/entity/list/' + dataField + '/' + role + '/{query}',   
            cache: false        
        },       
        filterRemoteData: false                
    });    
});