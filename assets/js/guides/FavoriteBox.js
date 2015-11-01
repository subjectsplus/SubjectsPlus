/**
* Setup for the favorite box that appears on the inital flyout.
* 
* 
* @constructor FavoriteBox
* 
*  
**/
/*jslint browser: true*/
/*global $, jQuery, alert*/
function FavoriteBox() {

    var myFavoriteBox = {

        settings: {
            favoritesUrl: "helpers/favorite_pluslets_data.php?staff_id=",
            favoriteBoxList: $(".fav-boxes-list")
        },
        strings: {
            copyCloneButtons: "<div class='pure-u-2-5' style='text-align:right;'><button class='clone-button pure-button pure-button-secondary'>Link</button>&nbsp;<button class='copy-button pure-button pure-button-secondary'>Copy</button></div></div></li>",
            noFavoritesText: "<li>No boxes have been marked as a favorite. To do so, click the gears button on the box you wish to mark as a Favorite and activate the Favorite toggle switch.</li>"
        },
        bindUiActions: function () {
        },
        init: function () {
            myFavoriteBox.getUserFavoriteBoxes();
        },
        getUserFavoriteBoxes: function () {

            var g = Guide();
            var staffId = g.getStaffId();

            myFavoriteBox.settings.favoriteBoxList.empty();
            $.ajax({
                url: myFavoriteBox.settings.favoritesUrl + staffId,
                type: "GET",
                dataType: "json",
                data: { staff_id: staffId },
                success: function (data) {

                    if (!data.length) {
                        myFavoriteBox.settings.favoriteBoxList.append(myFavoriteBox.strings.noFavoritesText);
                    }

                    $.each(data, function (idx, obj) {
                        myFavoriteBox.settings.favoriteBoxList.append("<li data-pluslet-id='" + obj.id + "'><div class='pure-g'><div class='pure-u-3-5 fav-box-item' title='" + obj.title + "'>" + obj.title + "</div>" + myFavoriteBox.strings.copyCloneButtons);

                    });
                }
            });

        },
    };

    return myFavoriteBox;
}
