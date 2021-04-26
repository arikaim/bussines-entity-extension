'use strict';

arikaim.component.onLoaded(function() {
    initCurrenyForm();
    
    $('.currency-dropdown').dropdown({
        onChange: function(value) {
           
            arikaim.page.loadContent({
                id: 'edit_currency_content',           
                component: 'currency::admin.currency.form',
                params: { uuid: value }
            },function(result) {
                initCurrenyForm();
            }); 
        }
    });

    function initCurrenyForm() {
        arikaim.ui.form.onSubmit("#currency_form",function() {  
            return currency.update('#currency_form');
        },function(result) {       
            arikaim.ui.form.showMessage(result.message);
        });
    }   
});