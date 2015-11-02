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
            myFavoriteBox.markAsFavorite();
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
        markAsFavorite: function () {

            /**
             * Created by cbrownroberts on 8/28/15.
             */

            //identify pluslets marked as favorites and addClass favorite_pluslet
            var $favBoxes = $('input.favorite_pluslet_input:checked')

            $favBoxes.each(function () {
                $(this).parent().parent().parent().parent().find('.titlebar_text').addClass('favorite_pluslet');

            });

        }
    };

    return myFavoriteBox;
}
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
        bindUiActions: function() {},
        init: function() {
            myFavoriteBox.getUserFavoriteBoxes();
            myFavoriteBox.markAsFavorite();
        },
        getUserFavoriteBoxes: function() {

            var g = Guide();
            var staffId = g.getStaffId();

            myFavoriteBox.settings.favoriteBoxList.empty();
            $.ajax({
                url: myFavoriteBox.settings.favoritesUrl + staffId,
                type: "GET",
                dataType: "json",
                data: {
                    staff_id: staffId
                },
                success: function(data) {

                    if (!data.length) {
                        myFavoriteBox.settings.favoriteBoxList.append(myFavoriteBox.strings.noFavoritesText);
                    }

                    $.each(data, function(idx, obj) {
                        myFavoriteBox.settings.favoriteBoxList.append("<li data-pluslet-id='" + obj.id + "'><div class='pure-g'><div class='pure-u-3-5 fav-box-item' title='" + obj.title + "'>" + obj.title + "</div>" + myFavoriteBox.strings.copyCloneButtons);

                    });
                }
            });

        },
        markAsFavorite: function() {

            /**
             * Created by cbrownroberts on 8/28/15.
             */

            //identify pluslets marked as favorites and addClass favorite_pluslet
            var $favBoxes = $('input.favorite_pluslet_input:checked')

            $favBoxes.each(function() {
                $(this).parent().parent().parent().parent().find('.titlebar_text').addClass('favorite_pluslet');

            });

        }
    };

    return myFavoriteBox;
}/**
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
        bindUiActions: function() {},
        init: function() {
            myFavoriteBox.getUserFavoriteBoxes();
            myFavoriteBox.markAsFavorite();
        },
        getUserFavoriteBoxes: function() {

            var g = Guide();
            var staffId = g.getStaffId();

            myFavoriteBox.settings.favoriteBoxList.empty();
            $.ajax({
                url: myFavoriteBox.settings.favoritesUrl + staffId,
                type: "GET",
                dataType: "json",
                data: {
                    staff_id: staffId
                },
                success: function(data) {

                    if (!data.length) {
                        myFavoriteBox.settings.favoriteBoxList.append(myFavoriteBox.strings.noFavoritesText);
                    }

                    $.each(data, function(idx, obj) {
                        myFavoriteBox.settings.favoriteBoxList.append("<li data-pluslet-id='" + obj.id + "'><div class='pure-g'><div class='pure-u-3-5 fav-box-item' title='" + obj.title + "'>" + obj.title + "</div>" + myFavoriteBox.strings.copyCloneButtons);

                    });
                }
            });

        },
        markAsFavorite: function() {

            /**
             * Created by cbrownroberts on 8/28/15.
             */

            //identify pluslets marked as favorites and addClass favorite_pluslet
            var $favBoxes = $('input.favorite_pluslet_input:checked')

            $favBoxes.each(function() {
                $(this).parent().parent().parent().parent().find('.titlebar_text').addClass('favorite_pluslet');

            });

        }
    };

    return myFavoriteBox;
}
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
        bindUiActions: function() {},
        init: function() {
            myFavoriteBox.getUserFavoriteBoxes();
            myFavoriteBox.markAsFavorite();
        },
        getUserFavoriteBoxes: function() {

            var g = Guide();
            var staffId = g.getStaffId();

            myFavoriteBox.settings.favoriteBoxList.empty();
            $.ajax({
                url: myFavoriteBox.settings.favoritesUrl + staffId,
                type: "GET",
                dataType: "json",
                data: {
                    staff_id: staffId
                },
                success: function(data) {

                    if (!data.length) {
                        myFavoriteBox.settings.favoriteBoxList.append(myFavoriteBox.strings.noFavoritesText);
                    }

                    $.each(data, function(idx, obj) {
                        myFavoriteBox.settings.favoriteBoxList.append("<li data-pluslet-id='" + obj.id + "'><div class='pure-g'><div class='pure-u-3-5 fav-box-item' title='" + obj.title + "'>" + obj.title + "</div>" + myFavoriteBox.strings.copyCloneButtons);

                    });
                }
            });

        },
        markAsFavorite: function() {

            /**
             * Created by cbrownroberts on 8/28/15.
             */

            //identify pluslets marked as favorites and addClass favorite_pluslet
            var $favBoxes = $('input.favorite_pluslet_input:checked')

            $favBoxes.each(function() {
                $(this).parent().parent().parent().parent().find('.titlebar_text').addClass('favorite_pluslet');

            });

        }
    };

    return myFavoriteBox;
}1.5.10
