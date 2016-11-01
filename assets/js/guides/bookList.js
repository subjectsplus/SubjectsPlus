function bookList() {


    var validGoogleBooksAPIKey = false;
    var validSyndeticsClientCode = false;
    var isPrimoConfigured = false;
    var view = 'control';

    var validateCodes = {
        validateSyndeticsClientCode: function (syndeticsClientCode) {
            if (syndeticsClientCode.trim()) {
                var result = true;
                $.ajax({
                    url: "http://syndetics.com/index.aspx?isbn=9780605039070/xml.xml&client=" + syndeticsClientCode + "&type=rn12",
                    statusCode: {
                        500: function () {
                            result = false;
                        }
                    },
                    async: false
                });
                validSyndeticsClientCode = result;
            }
        },
        validateGoogleBooksAPIKey: function (googleBooksAPIKey) {
            if (googleBooksAPIKey.trim()) {
                var result = true;
                $.ajax({
                    url: "https://www.googleapis.com/books/v1/volumes?key=" + googleBooksAPIKey + "&q=9780605039070",
                    statusCode: {
                        400: function () {
                            result = false;
                        }
                    },
                    async: false
                });
                validGoogleBooksAPIKey = result;
            }
        },
        isPrimoConfigured: function (container) {
            var primoDomain = container.getElementsByTagName('input')[3].value;
            var primoInstitutionCode = container.getElementsByTagName('input')[4].value;
            var primoView = container.getElementsByTagName('input')[5].value;

            if (primoDomain && primoInstitutionCode && primoView){
                isPrimoConfigured = true;
            }
        }
    };

    var myBookList = {

        bindUiActions: function (container) {
            myBookList.getBookList(container);
        },
        bindUiActionsForEditView: function () {
            myBookList.validCharacters();
            myBookList.isNumberKey();
        },
        init: function (container) {
            var syndeticsClientCode = container.getElementsByTagName('input')[1].value;
            var googleBooksAPIKey = container.getElementsByTagName('input')[2].value;

            validateCodes.validateGoogleBooksAPIKey(googleBooksAPIKey);
            validateCodes.validateSyndeticsClientCode(syndeticsClientCode);
            validateCodes.isPrimoConfigured(container);

            var url = document.location.href;

            if (url.indexOf('control') === -1) {
                view = 'live';
            }

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

            if (isPrimoConfigured) {
                $.ajax({
                    type: "GET",
                    url: myBookList.getUrlPrefix() + 'subjects/includes/isbn_in_primo_check.php',
                    data: {
                        "isbn": data.isbn
                    },
                    dataType: "text",
                    success: function (anchorUrl) {
                        if (anchorUrl.trim()){
                            myBookList.insertBookInformation(data, container, coverPath, anchorUrl);
                        }else{
                            myBookList.insertBookInformation(data, container, coverPath, '');
                        }
                    }
                });
            }else{
                myBookList.insertBookInformation(data, container, coverPath, '');
            }
        },
        insertBookInformation: function (data, container, coverPath, anchorUrl) {
            var br = document.createElement('br');
            var divContent = document.createElement('div');
            divContent.classList.add('booklist_isbn_data');

            myBookList.setBookCoverSrc(container, coverPath, anchorUrl);

            var bookTitle = data.title;
            var titleHeader = document.createElement('h4');
            titleHeader.setAttribute('data-book-title', bookTitle);
            titleHeader.innerHTML = bookTitle;
            titleHeader.appendChild(br);

            if (anchorUrl) {
                var anchor = document.createElement('a');
                anchor.setAttribute('href', anchorUrl);
                anchor.setAttribute('target', '_blank');
                anchor.appendChild(titleHeader);
                divContent.appendChild(anchor);
            }else{
                divContent.appendChild(titleHeader);
            }

            var br = document.createElement('br');
            var authorsList = data.author;
            var authorsListP = document.createElement('p');
            authorsListP.classList.add('booklist-author');
            authorsListP.setAttribute('data-book-author', authorsList);
            authorsListP.innerHTML = authorsList;
            authorsListP.appendChild(br);
            divContent.appendChild(authorsListP);

            var date = data.date;
            var publishedDate = document.createElement('p');
            publishedDate.classList.add('booklist-date');
            publishedDate.setAttribute('data-book-pubdate', date);
            publishedDate.innerHTML = date;
            publishedDate.appendChild(br);
            divContent.appendChild(publishedDate);
            container.appendChild(divContent);

            var divItem = divContent.previousSibling;
            divItem.appendChild(divContent);
        },
        getBookList: function (container) {
            var data = container.getElementsByTagName('input')[0].value;
            var syndeticsClientCode = container.getElementsByTagName('input')[1].value;
            var googleBooksAPIKey = container.getElementsByTagName('input')[2].value;
            var regex = new RegExp('(ISBN[-]*(1[03])*[ ]*(: ){0,1})*(([0-9Xx][- ]*){13}|([0-9Xx][- ]*){10})');
            var prefix = myBookList.getUrlPrefix();

            if (data != undefined) {
                if (data.trim()) {
                    var arr = data.split(',');

                    for (var j = 0; j < arr.length; j++) {
                        var isbn = arr[j];

                        if (isbn.trim()) {
                            if (regex.test(isbn)) {
                               myBookList.processISBN(isbn, prefix, googleBooksAPIKey, syndeticsClientCode, container);
                            } else {
                                if (view === 'control') {
                                    myBookList.setNumberErrorMessage(isbn, container);
                                }
                            }
                        }
                    }
                }
            }
        },
        processISBN: function (isbn, prefix, googleBooksAPIKey, syndeticsClientCode, container) {
            $.when(myBookList.isFileInCache(prefix + 'assets/cache/' + isbn + '.bookmetadata', "GET"), myBookList.isFileInCache(prefix + 'assets/cache/' + isbn + '.jpg'), "HEAD").then(function(a1,a2){
                var dataResponse = a1[0];
                dataResponse = $.parseJSON(dataResponse);
                myBookList.populatePlusletViewFromCache(container, dataResponse, prefix + 'assets/cache/' + isbn + '.jpg');
            }, function () {
                myBookList.updateBookCache(container, isbn, googleBooksAPIKey, syndeticsClientCode);
            });
        },
        isFileInCache: function (url, type) {
            return $.ajax({
                type: type,
                dataType: "text",
                url: url
            });
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
            var coverPath = '';
            var coverPathSyndetics = '';
            var url = '';
            var result = {isbn: []};

            if (validSyndeticsClientCode) {
                coverPath = myBookList.getCoverPathFromSyndetics(container, isbn, syndeticsClientCode);
                coverPathSyndetics = coverPath;
            }

            if (validGoogleBooksAPIKey) {
                url = "https://www.googleapis.com/books/v1/volumes?key=" + googleBooksAPIKey + "&q=isbn:" + isbn;

                $.ajax({
                    type: "GET",
                    url: url,
                    dataType: "text",
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
                            if (view === 'control') {
                                myBookList.setNumberErrorMessage(isbn, container);
                            }
                        }
                    }
                });
            } else {

                var validOpenLibrary = myBookList.getMetaDataFromOpenLibrary(container, isbn, coverPathSyndetics);
            }
        },
        isFileInCache: function (url, type) {
            return $.ajax({
                type: type,
                dataType: "text",
                url: url
            });
        },
        getBookDataFromOpenLibrary: function (isbn) {
            return $.ajax({
                type: "GET",
                url: myBookList.getUrlPrefix() + 'subjects/includes/book_metadata_from_open_library.php',
                dataType: "text",
                data: {
                    "isbn": isbn,
                }
            });
        },
        getBookCoverFromOpenLibrary: function (isbn) {
            return $.ajax({
                type: "GET",
                url: myBookList.getUrlPrefix() + 'subjects/includes/book_cover_from_open_library.php',
                dataType: "text",
                data: {
                    "isbn": isbn,
                }
            });
        },
        getMetaDataFromOpenLibrary: function (container, isbn, coverPathSyndetics) {
            var coverPath = '';
            var validOpenLibraryData = false;
            var validSyndetics = false;
            var result = {isbn: []};
            var succeed = false;

            $.when(myBookList.getBookDataFromOpenLibrary(isbn), myBookList.getBookCoverFromOpenLibrary(isbn)).then(function(a1,a2){

                if (!coverPathSyndetics.trim()){
                    if (a2[1] === 'success') {
                        coverPath = a2[0];
                    }
                }else{
                    validSyndetics = true;
                }

                if (a1[1] === 'success'){
                    var obj = $.parseJSON(a1[0]);

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

                if (validSyndeticsClientCode && !validGoogleBooksAPIKey && view === 'control') {
                    myBookList.setNoSourceAvailableErrorMessage(container, isbn);
                }

                if (!validOpenLibraryData && view === 'control') {
                    myBookList.setNoSourceAvailableErrorMessage(container, isbn);
                }
            });
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
                dataType: "text"
            });
        },
        setNumberErrorMessage: function (isbn, container) {
            var checkNumberMessage = document.createElement('p');
            checkNumberMessage.innerHTML = "Please check this number: " + isbn;
            var divBook = document.createElement('div');
            divBook.classList.add('booklist-alert');
            divBook.appendChild(checkNumberMessage);
            container.appendChild(divBook);
        },
        setNoSourceAvailableErrorMessage: function (container, isbn) {
            var checkNumberMessage = document.createElement('p');
            checkNumberMessage.innerHTML = "Sorry, there is not information for this book." + isbn;
            var divBook = document.createElement('div');
            divBook.classList.add('booklist-alert');
            divBook.appendChild(checkNumberMessage);
            container.appendChild(divBook);
        },
        setGoogleBooksAPIErrorMessage: function (container) {
            var checkNumberMessage = document.createElement('p');
            checkNumberMessage.innerHTML = "Please check the Google Books API key";
            var divBook = document.createElement('div');
            divBook.classList.add('booklist-alert');
            divBook.appendChild(checkNumberMessage);
            container.appendChild(divBook);
        },
        setSyndeticsClientCodeErrorMessage: function (container) {
            var checkNumberMessage = document.createElement('p');
            checkNumberMessage.innerHTML = "Please check the Syndetics Client Code";
            var divBook = document.createElement('div');
            divBook.classList.add('booklist-alert');
            divBook.appendChild(checkNumberMessage);
            container.appendChild(divBook);
        },
        setBookCoverSrc: function (container, url, anchorUrl){
            var imgCover = document.createElement('img');
            imgCover.setAttribute('src', url);
            imgCover.setAttribute('data-show-image', url);

            var divItem = document.createElement('div');
            divItem.classList.add('booklist_item');

            var divCover = document.createElement('div');
            divCover.classList.add('booklist_isbn_cover');

            if (anchorUrl){
                var anchor = document.createElement('a');
                anchor.setAttribute('href', anchorUrl);
                anchor.setAttribute('target', '_blank');

                anchor.appendChild(imgCover);
                divCover.appendChild(anchor);
            }else{
                divCover.appendChild(imgCover);
            }

            divItem.appendChild(divCover);
            container.appendChild(divItem);
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
