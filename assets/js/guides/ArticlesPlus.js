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
        searchArticlesPlus: function () {

            $(".pluslet-articles-plus-form-button").click(function (e) {
                var form =  $(this).parents('form:first');
                var primoQueryArticlesTempValue = form.find('#primoQueryTempArticles').val();
                form.find('#primoQueryArticles').val("any,contains," + primoQueryArticlesTempValue.replace(/[,]/g, " "));
                form.submit();
            });

        }
    };

    return myArticlesPlus;
}