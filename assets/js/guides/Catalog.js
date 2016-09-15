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

            $('#search-primo-catalog').on('click', function () {
                var searchfield = document.getElementById("primo-catalog-searchtype").value;
                document.getElementById("primo-catalog-query").value = searchfield + ",contains," + document.getElementById("primo-catalog-query-temp").value.replace(/[,]/g, " ");
                document.forms["search-primo-catalog-form"].submit();

            });

        }
    };

    return myPrimoCatalog;
}