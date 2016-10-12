function bookList() {

    var myBookList = {

        settings: {},
        strings: {},

        bindUiActions: function (container) {
            myBookList.getBookList(container);
        },
        bindUiActionsForEditView: function () {
            myBookList.validCharacters();
            myBookList.isNumberKey();
        },
        init: function (container) {
            myBookList.bindUiActions(container);
        },
        initEditView: function () {
            myBookList.bindUiActionsForEditView();
        },
        validCharacters: function () {
            $('textarea[name=BookList-extra-isbn]').on('paste', function() {
                var $el = $(this);
                setTimeout(function() {
                    $el.val(function(i, val) {
                        return val.replace(/[^0-9X,]/g, '')
                    })
                })
            });
        },
        isNumberKey: function ()
        {
            $('textarea[name=BookList-extra-isbn]').keypress(function(evt) {
                var result = true;
                var charCode = (evt.which) ? evt.which : event.keyCode;
                if (charCode < 48 || charCode > 57)
                    result = false;

                if (charCode == 88 || charCode == 44)
                    result = true;

                return result;
            });
        },
        populatePlusletViewFromCache: function(container, response, coverPath){
            var data = response.isbn;

            if( Object.prototype.toString.call( data ) === '[object Array]' ) {
                var text = '{ "title":"'+data[0].title+'" , "author":"'+data[1].author+'",' +
                    '"date":"'+data[2].date+'" }';

                var obj = JSON.parse(text);
                data = obj;
            }

            var br = document.createElement('br');
            var divContent = document.createElement('div');

            myBookList.setBookCoverSrc(container, coverPath);

            var bookTitle = data.title;
            var titleHeader = document.createElement('h2');
            titleHeader.setAttribute('data-book-title', bookTitle);
            titleHeader.innerHTML = bookTitle;
            titleHeader.appendChild(br);
            divContent.appendChild(titleHeader);

            var authorsList = data.author;
            var authorsListP = document.createElement('p');
            authorsListP.setAttribute('data-book-author', authorsList);
            authorsListP.innerHTML = authorsList;
            authorsListP.appendChild(br);
            divContent.appendChild(authorsListP);

            var date = data.date;
            var publishedDate = document.createElement('p');
            publishedDate.setAttribute('data-book-pubdate', date);
            publishedDate.innerHTML = date;
            publishedDate.appendChild(br);
            divContent.appendChild(publishedDate);
            container.appendChild(divContent);
        },
        getBookList: function (container) {
            var data = container.getElementsByTagName('input')[0].value;
            var syndeticsClientCode = container.getElementsByTagName('input')[1].value;
            var googleBooksAPIKey = container.getElementsByTagName('input')[2].value;
            var regex = new RegExp('(ISBN[-]*(1[03])*[ ]*(: ){0,1})*(([0-9Xx][- ]*){13}|([0-9Xx][- ]*){10})');

            if (data != undefined) {
                if (data.trim()) {
                    var arr = data.split(',');

                    for (var j = 0; j < arr.length; j++) {
                        var isbn = arr[j];

                        if (isbn.trim()) {
                            if (regex.test(isbn)) {
                                var metadataCachePath = '';
                                var coverCachePath = '';

                                var prefix = myBookList.getUrlPrefix();

                                $.ajax({
                                    type: "GET",
                                    url: prefix + 'assets/cache/' + isbn + '.bookmetadata',
                                    dataType: "text",
                                    async: false,
                                    success: function (data) {
                                        metadataCachePath = $.parseJSON(data);
                                    }
                                });
                                $.ajax({
                                    type: "GET",
                                    url: prefix + 'assets/cache/' + isbn + '.jpg',
                                    dataType: "text",
                                    async: false,
                                    success: function () {
                                        coverCachePath = prefix + 'assets/cache/' + isbn + '.jpg';
                                    }
                                });

                                if (metadataCachePath && coverCachePath) {
                                    var metadata = metadataCachePath;
                                    myBookList.populatePlusletViewFromCache(container, metadata, coverCachePath);
                                }
                                else {
                                    myBookList.updateBookCache(container, isbn, googleBooksAPIKey, syndeticsClientCode);
                                }
                            } else {
                                myBookList.setNumberErrorMessage(isbn, container);
                            }
                        }
                    }
                }
            }
        },
        getUrlPrefix : function () {
            var value = '';
            var url = document.location.href;

            if (url.indexOf('control') !== -1){
                value = document.location.href.split("control")[0];
            }else if (url.indexOf('subjects') !== -1){
                value = document.location.href.split("subjects")[0];
            }
            return value;
        },
        validateSyndeticsClientCode: function (syndeticsClientCode, container){
            var result =  true;
            $.ajax({
                url: "http://syndetics.com/index.aspx?isbn=9780605039070/xml.xml&client=" + syndeticsClientCode + "&type=rn12",
                statusCode: {
                    500: function() {
                        myBookList.setSyndeticsClientCodeErrorMessage(container);
                        result = false;
                    }
                },
                async: false
            });

            return result;
        },
        validateGoogleBooksAPIKey: function (googleBooksAPIKey, container, isbn){
            var result =  true;
            $.ajax({
                url: "https://www.googleapis.com/books/v1/volumes?key=" + googleBooksAPIKey + "&q=" + isbn,
                statusCode: {
                    400: function() {
                        myBookList.setGoogleBooksAPIErrorMessage(container);
                        result = false;
                    }
                },
                async: false
            });

            return result;
        },
        getCoverPathFromSyndetics: function (container, isbn, syndeticsClientCode){
            var result = '';

            $.ajax({
                type: "GET",
                url: myBookList.getUrlPrefix() + 'subjects/includes/syndetics_image_xml_data.php',
                data: {
                    "isbn": isbn,
                    "syndeticsClientCode": syndeticsClientCode
                },
                dataType: "text",
                async: false,
                success: function (data) {
                    if (data.trim()){
                        result = data;
                    }
                }
            });

            return result;
        },
        updateBookCache: function (container, isbn, googleBooksAPIKey, syndeticsClientCode){
            var validSyndeticsCode = false;
            var coverPath = '';
            var coverPathSyndetics = '';
            var validGoogleBooksAPIKey = false;
            var url = '';
            var result = {isbn:[]};

            if (syndeticsClientCode.trim()) {
                validSyndeticsCode = myBookList.validateSyndeticsClientCode(syndeticsClientCode, container);

                if (validSyndeticsCode) {
                    coverPath = myBookList.getCoverPathFromSyndetics(container, isbn, syndeticsClientCode);
                    coverPathSyndetics = coverPath;
                }
            }

            if (googleBooksAPIKey.trim()) {
                validGoogleBooksAPIKey = myBookList.validateGoogleBooksAPIKey(googleBooksAPIKey, container, isbn);

                if (validGoogleBooksAPIKey) {
                    url = "https://www.googleapis.com/books/v1/volumes?key=" + googleBooksAPIKey + "&q=isbn:" + isbn;

                    $.ajax({
                        type: "GET",
                        url: url,
                        dataType: "text",
                        async: false,
                        success: function (data) {
                            var obj = $.parseJSON(data);
                            if (obj.totalItems != 0) {
                                obj = obj.items[0];

                                result.isbn.push({
                                    "title": obj.volumeInfo.title
                                });
                                result.isbn.push({
                                    "author": obj.volumeInfo.authors.join(", ")
                                });
                                result.isbn.push({
                                    "date": myBookList.formatDate(obj.volumeInfo.publishedDate)
                                });

                                if (!coverPathSyndetics) {
                                    if (typeof obj.volumeInfo.imageLinks !== 'undefined') {
                                        coverPath = obj.volumeInfo.imageLinks.thumbnail;
                                    }
                                }

                                myBookList.populatePlusletViewFromCache(container, result, coverPath);
                                myBookList.downloadCoverToCache(coverPath, isbn);
                                myBookList.downloadDataToCache(result, isbn);

                            } else {
                                myBookList.setNumberErrorMessage(isbn, container);
                            }
                        }
                    });
                }
            }else {

                var validOpenLibrary = myBookList.getMetaDataFromOpenLibrary(container, isbn, coverPathSyndetics);

                if (validSyndeticsCode && !validGoogleBooksAPIKey) {
                    myBookList.setNoSourceAvailableErrorMessage(container);
                }

                if (!validOpenLibrary) {
                    myBookList.setNoSourceAvailableErrorMessage(container);
                }
            }
        },
        getMetaDataFromOpenLibrary: function (container, isbn, coverPathSyndetics){
            var coverPath = '';
            var validOpenLibraryData = false;
            var validSyndetics = false;
            var url = '';
            var result = {isbn:[]};
            var succeed = false;

            if (!coverPathSyndetics.trim()) {
                $.ajax({
                    type: "GET",
                    url: myBookList.getUrlPrefix() + 'subjects/includes/book_cover_from_open_library.php',
                    dataType: "text",
                    async: false,
                    data: {
                        "isbn": isbn,
                    },
                    success: function (data) {
                        coverPath = data;
                    }
                });
            }else{
                validSyndetics = true;
            }

            $.ajax({
                type: "GET",
                url: myBookList.getUrlPrefix() + 'subjects/includes/book_metadata_from_open_library.php',
                dataType: "text",
                data: {
                    "isbn": isbn,
                },
                async: false,
                success: function (data) {
                    var obj = $.parseJSON(data);

                    if (obj.isbn.length != 0){
                        validOpenLibraryData = true;
                        obj = obj.isbn;

                        result.isbn.push({
                            "title": obj.title
                        });
                        result.isbn.push({
                            "author": obj.author.join(", ")
                        });
                        result.isbn.push({
                            "date": myBookList.formatDate(obj.date)
                        });
                    }
                }
            });

            if (validSyndetics && validOpenLibraryData){
                myBookList.populatePlusletViewFromCache(container, result, coverPathSyndetics);
                myBookList.downloadCoverToCache(coverPathSyndetics, isbn);
                myBookList.downloadDataToCache(result, isbn);
                succeed = true;
            }else if (validOpenLibraryData) {
                myBookList.populatePlusletViewFromCache(container, result, coverPath);
                myBookList.downloadCoverToCache(coverPath, isbn);
                myBookList.downloadDataToCache(result, isbn);
                succeed = true;
            }

            return succeed;

        },
        formatAuthorsFromOpenLibrary : function (authors) {
            var names = Array();

            for (var name in authors){
                names.push(authors);
            }

            return names.join(", ");
        },
        downloadDataToCache: function (data, isbn) {
            $.ajax({
                type: "GET",
                url: myBookList.getUrlPrefix() + 'subjects/includes/book_metadata_download.php',
                data: {
                    "title": data.isbn[0].title,
                    "isbn": isbn,
                    "author": data.isbn[1].author,
                    "date": data.isbn[2].date
                },
                async: false,
                dataType: "text"
            });
        },
        downloadCoverToCache: function (url, isbn) {
            $.ajax({
                type: "GET",
                url: myBookList.getUrlPrefix() + 'subjects/includes/book_cover_download.php',
                data: {
                    "url": url,
                    "isbn": isbn
                },
                async: false,
                dataType: "text"
            });
        },
        setNumberErrorMessage: function (isbn, container) {
            var checkNumberMessage = document.createElement('h2');
            checkNumberMessage.innerHTML = "Please check this number: " + isbn;
            var divBook = document.createElement('div');
            divBook.appendChild(checkNumberMessage);
            container.appendChild(divBook);
        },
        setNoSourceAvailableErrorMessage: function (container) {
            var checkNumberMessage = document.createElement('h2');
            checkNumberMessage.innerHTML = "Sorry, there is not information for this book.";
            var divBook = document.createElement('div');
            divBook.appendChild(checkNumberMessage);
            container.appendChild(divBook);
        },
        setGoogleBooksAPIErrorMessage: function (container) {
            var checkNumberMessage = document.createElement('h2');
            checkNumberMessage.innerHTML = "Please check the Google Books API key";
            var divBook = document.createElement('div');
            divBook.appendChild(checkNumberMessage);
            container.appendChild(divBook);
        },
        setSyndeticsClientCodeErrorMessage: function (container) {
            var checkNumberMessage = document.createElement('h2');
            checkNumberMessage.innerHTML = "Please check the Syndetics Client Code";
            var divBook = document.createElement('div');
            divBook.appendChild(checkNumberMessage);
            container.appendChild(divBook);
        },
        setBookCoverSrc: function (container, url){
            var imgCover = document.createElement('img');
            imgCover.setAttribute('src', url);
            imgCover.setAttribute('data-show-image', url);

            var divCover = document.createElement('div');
            divCover.appendChild(imgCover);

            container.appendChild(divCover);
        },
        getUrl: function (url) {
            // Return a new promise.
            return new Promise(function(resolve, reject) {
                // Do the usual XHR stuff
                var req = new XMLHttpRequest();
                req.open('GET', url);

                req.onload = function() {
                    // This is called even on 404 etc
                    // so check the status
                    if (req.status == 200) {
                        // Resolve the promise with the response text
                        resolve(req.response);
                    }
                    else {
                        // Otherwise reject with the status text
                        // which will hopefully be a meaningful error
                        reject(Error(req.statusText));
                    }
                };

                // Handle network errors
                req.onerror = function() {
                    reject(Error("Network Error"));
                };

                // Make the request
                req.send();
            });
        },
        formatDate: function (date) {
            var msec = Date.parse(date);
            var newDate = new Date(msec);

            return newDate.getFullYear();
        }
    };

    return myBookList;
}
