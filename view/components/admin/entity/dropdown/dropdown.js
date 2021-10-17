'use strict';

arikaim.component.onLoaded(function() {
    var dataField = $('.entity-dropdown').attr('data-field');
    
    var v = {
        apiSettings: {     
            on: 'now',      
            url: arikaim.getBaseUrl() + '/api/address/city/list/' + dataField + '/{query}',   
            cache: false        
        },       
        filterRemoteData: false         
    };
    $('.entity-dropdown').dropdown({});
});