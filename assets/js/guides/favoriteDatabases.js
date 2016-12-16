/**
 * Created by acarrasco on 12/16/2016.
 */

function favoriteDatabasesList() {

    var favoriteDatabasesList = {

        bindUiActions: function () {
            favoriteDatabasesList.addDatabaseToFavorites();
            favoriteDatabasesList.exportFavoriteDatabasesList();
            favoriteDatabasesList.importFavoriteDatabases();
            favoriteDatabasesList.favoriteDatabasesListInpput();
        },
        init: function () {
            favoriteDatabasesList.setFavoriteDatabases();
            favoriteDatabasesList.showFavoriteDatabases();
            favoriteDatabasesList.bindUiActions();
        },
        addDatabaseToFavorites: function () { //add database to favorites using HTML5 localStorage
            $(".favorite-database-icon").click(function () {
                if (typeof(Storage) !== "undefined") {
                    var databaseName = $(this).parent().parent().find("a").text();
                    var databaseUrl = $(this).parent().parent().find("a").attr('href');
                    if ($(this).hasClass("fa-star-o")) {
                        //mark as favorite
                        favoriteDatabasesList.markAsFavorite(this);
                        if (!$.isEmptyObject(localStorage.favoriteDatabases)) {
                            favoriteDatabasesList.saveFavoriteDatabasesToLocalStorage(databaseName, databaseUrl);
                        } else {
                            localStorage.favoriteDatabases = "[]";
                            favoriteDatabasesList.saveFavoriteDatabasesToLocalStorage(databaseName, databaseUrl);
                        }
                    } else {
                        //unmark as favorite
                        $(this).removeClass("fa-star");
                        $(this).addClass("fa-star-o");
                        favoriteDatabasesList.deleteFavoriteDatabaseFromLocalStorage(databaseName, databaseUrl);
                    }
                }
            })
        },
        getInternetExplorerVersion: function () {
            var ua = window.navigator.userAgent;
            var msie = ua.indexOf("MSIE ");

            if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))
                return parseInt(ua.substring(msie + 5, ua.indexOf(".", msie)));
            else
                return 0

        },
        saveOnInternetExplorer: function () {
            var blobObject = new Blob([localStorage.favoriteDatabases]);
            window.navigator.msSaveBlob(blobObject, 'myFavoriteDatabasesList');
        },
        saveOnDisk: function () {
            var favoriteDatabases = localStorage.favoriteDatabases;
            uriContent = "data:application/octet-stream," + encodeURIComponent(favoriteDatabases);
            var link = document.createElement('a');
            if (typeof link.download === 'string') {
                link.href = uriContent;
                link.setAttribute('download', "myFavoriteDatabasesList");
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            } else {
                window.open(uriContent);
            }
        },
        exportFavoriteDatabasesList: function () {
            $("#exportFavoriteDatabases").click(function () {
                    if (typeof(Storage) !== "undefined") {

                        if (!$.isEmptyObject(localStorage.favoriteDatabases)) {
                            if (favoriteDatabasesList.getInternetExplorerVersion() != 0) {
                                favoriteDatabasesList.saveOnInternetExplorer();
                            } else {
                                favoriteDatabasesList.saveOnDisk();
                            }
                        }
                    }
                }
            )
        },
        importFavoriteDatabases: function () {
            $("#importFavoriteDatabases").click(function () {
                    $('#favoriteDatabasesListInput').trigger('click');
                }
            );
        },
        favoriteDatabasesListInpput: function () {
            $("#favoriteDatabasesListInput").change(function (event) {
                    var files = event.target.files;
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        try {
                            var favoriteDatabases = JSON.parse(e.target.result);

                            for (var i = 0, len = favoriteDatabases.length; i < len; i++) {
                                var objectLength = Object.keys(favoriteDatabases[i]).length;
                                if (objectLength != 2) {
                                    throw 'error';
                                } else if (!('databaseName' in favoriteDatabases[i]) && !('databaseUrl' in favoriteDatabases[i])) {
                                    throw 'error';
                                }
                            }

                            localStorage.favoriteDatabases = JSON.stringify(favoriteDatabases);
                            location.reload();
                        } catch (e) {
                            alert('The favorite databases list file is either corrupted or wrong!');
                        }
                    };
                    reader.readAsText(files[0]);
                }
            );
        },
        saveFavoriteDatabasesToLocalStorage: function (databaseName, databaseUrl) {
            var favoriteDatabases = JSON.parse(localStorage.favoriteDatabases);
            favoriteDatabases.push({"databaseName": databaseName, "databaseUrl": databaseUrl});
            localStorage.favoriteDatabases = JSON.stringify(favoriteDatabases);
        },
        deleteFavoriteDatabaseFromLocalStorage: function (databaseName, databaseUrl) {
            var toDelete = {"databaseName": databaseName, "databaseUrl": databaseUrl};
            var favoriteDatabases = JSON.parse(localStorage.favoriteDatabases);
            var position = -1;
            for (var i = 0, len = favoriteDatabases.length; i < len; i++) {
                if (favoriteDatabases[i].databaseName == toDelete.databaseName && favoriteDatabases[i].databaseUrl == toDelete.databaseUrl) {
                    position = i;
                    break;
                }
            }
            if (position != -1) {
                favoriteDatabases.splice(position, 1);
            }
            localStorage.favoriteDatabases = JSON.stringify(favoriteDatabases);
        },
        setFavoriteDatabases: function () {
            if (!$.isEmptyObject(localStorage.favoriteDatabases)) {
                var favoriteDatabases = JSON.parse(localStorage.favoriteDatabases);
                $(".favorite-database-icon").each(function () {
                    var databaseName = $(this).parent().parent().find("a").text();
                    var databaseUrl = $(this).parent().parent().find("a").attr('href');
                    for (var i = 0, len = favoriteDatabases.length; i < len; i++) {
                        if (favoriteDatabases[i].databaseName == databaseName && favoriteDatabases[i].databaseUrl == databaseUrl) {
                            favoriteDatabasesList.markAsFavorite(this);
                            break;
                        }
                    }
                })
            }
        },
        markAsFavorite: function (button) {
            $(button).removeClass("fa-star-o");
            $(button).addClass("fa-star");
        },
        showFavoriteDatabases: function () {
            var favoriteDatabasesListDiv = document.getElementById('favoriteDatabasesList');
            var list = document.createElement('ul');
            if (!$.isEmptyObject(localStorage.favoriteDatabases)) {
                var favoriteDatabases = JSON.parse(localStorage.favoriteDatabases);
                for (var i = 0, len = favoriteDatabases.length; i < len; i++) {
                    var listItem = document.createElement('li');
                    var anchor = document.createElement('a');
                    anchor.setAttribute('href', favoriteDatabases[i].databaseUrl);
                    anchor.appendChild(document.createTextNode(favoriteDatabases[i].databaseName));

                    listItem.appendChild(anchor);
                    list.appendChild(listItem);
                }
                favoriteDatabasesListDiv.appendChild(list);
            }
        }};

    return favoriteDatabasesList;
}