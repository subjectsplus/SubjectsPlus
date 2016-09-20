function articlesPlus() {

    var myArticlesPlus = {

        settings: {},
        strings: {},
        bindUiActions: function () {
            myArticlesPlus.searchArticlesPlus();
        },
        init: function () {
            myArticlesPlus.bindUiActions();
        },
        searchArticlesPlus : function () {

            $('#search-articles-plus').on('click', function() {

                document.getElementById("primoQueryArticles").value = "any,contains," + document.getElementById("primoQueryTempArticles").value.replace(/[,]/g, " ");

                document.forms["searchFormPrimo"].submit();
            });

        }
    };

    return myArticlesPlus;
}