'use strict';

arikaim.component.onLoaded(function() {
    safeCall('entityView',function(obj) {
        obj.initRows();
    },true);    
});