function primoSearchBox() {

    var myPrimoSearchBox = {

        settings: {
            primoQuery: $('#primoQuery'),
            primoQueryTemp : $('#primo-search-box-query-temp'),
            rtype : $('#rtype'),
            precisionOperator : $('#precisionOperator'),
            advancedSearchContainer :$('#advanced_search_container'),
            advSearchBtn : $('#adv_search_btn'),
            searchPrimoBtn : $('#search-primo-btn')
        },
        strings: {},
        bindUiActions: function () {
            myPrimoSearchBox.toggleMoreOptions();
            myPrimoSearchBox.search();
        },
        init: function () {
            myPrimoSearchBox.bindUiActions();
            myPrimoSearchBox.hideMoreOptions();
        },

        search : function () {

            myPrimoSearchBox.settings.searchPrimoBtn.on('click', function () {

                if(myPrimoSearchBox.settings.rtype.val() && myPrimoSearchBox.settings.precisionOperator.val()) {
                    myPrimoSearchBox.settings.primoQuery.val(myPrimoSearchBox.settings.rtype.val() + ',' +
                                            myPrimoSearchBox.settings.precisionOperator.val() + ',' +
                                            myPrimoSearchBox.settings.primoQueryTemp.val().replace(/[,]/g, " "));
                } else {
                    myPrimoSearchBox.settings.primoQuery.val('any,contains,' + myPrimoSearchBox.settings.primoQueryTemp.val().replace(/[,]/g, " "));
                }

                document.forms["primo-search-form"].submit();
            });
        },

        hideMoreOptions : function () {
            myPrimoSearchBox.settings.advancedSearchContainer.hide();
        },

        toggleMoreOptions : function () {
            myPrimoSearchBox.settings.advSearchBtn.on('click', function () {
                myPrimoSearchBox.settings.advancedSearchContainer.toggle();
            });
        }
    };

    return myPrimoSearchBox;
}