function primoSearchBox() {

    var myPrimoSearchBox = {

        settings: {
        },
        strings: {},
        bindUiActions: function () {
            myPrimoSearchBox.toggleMoreOptions();
            myPrimoSearchBox.search();

        },
        init: function () {
            myPrimoSearchBox.bindUiActions();
            myPrimoSearchBox.hideMoreOptions();
            myPrimoSearchBox.toggleSearchScope();
        },

        search : function () {

            $(".search-primo-btn").on('click', function () {


                var form = $(this).parent().parent();
                var formChildren = $(form).children();

                var primoQuery = $(formChildren).find("[name='query']");
                var primoQueryTemp = $(formChildren).find("[name='primo-search-box-query-temp']");
                var rtype = $(formChildren).find("[name='rtype']");
                var precisionOperator = $(formChildren).find("[name='precisionOperator']");

                if(rtype.val() && precisionOperator.val()) {
                    primoQuery.val(rtype.val() + ',' + precisionOperator.val() + ',' + primoQueryTemp.val().replace(/[,]/g, " "));

                } else {
                    primoQuery.val('any,contains,' + primoQueryTemp.val().replace(/[,]/g, " "));
                }

                form.submit();
            });
        },

        hideMoreOptions : function () {
            $("[name='advanced_search_container']").hide();
        },

        toggleMoreOptions : function () {
           $("body").on('click', '[name="adv_search_btn"]', function () {
               $(this).parent().siblings("[name='advanced_search_container']").toggle();
           });
        },

        toggleSearchScope: function () {
            $('body').on('change', 'select[name="tab"]', function () {

                var search_scope = $(this).val();

                if( search_scope == 'default_tab') {
                    $(this).siblings('.search_scope').val('default_scope');
                } else {
                    $(this).siblings('.search_scope').val('Everything');
                }
            });
        }
    };

    return myPrimoSearchBox;
}