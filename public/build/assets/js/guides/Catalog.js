function primoCatalog() {

    var myPrimoCatalog = {

        settings: {},
        strings: {},
        bindUiActions: function () {
            myPrimoCatalog.search();
        },
        init: function () {
            myPrimoCatalog.bindUiActions();
        },
        search : function () {

            $('.pluslet-catalog-form-button').on('click', function () {
                var form =  $(this).parents('form:first');
                var searchfield = form.find('#primo-catalog-searchtype').val();
                var queryValue = form.find('#primo-catalog-query-temp').val();
                form.find('#primo-catalog-query').val(searchfield + ",contains," + queryValue.replace(/[,]/g, " "));
                form.submit();
            });

        }
    };

    return myPrimoCatalog;
}