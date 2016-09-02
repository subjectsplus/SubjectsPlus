function bookList() {

    "use strict";

    var myBookList = {

        settings: {},
        strings: {},
        bindUiActions: function () {
            myBookList.validCharacters();
            myBookList.isNumberKey();
            myBookList.getBookList();
        },
        init: function () {
            myBookList.bindUiActions();
        },
        validCharacters: function () {
            $('#target').on('paste', function() {
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
            $('#target').keypress(function(evt) {
                console.log('key')
                var result = true;
                var charCode = (evt.which) ? evt.which : event.keyCode;
                if (charCode < 48 || charCode > 57)
                    result = false;

                if (charCode == 88 || charCode == 44)
                    result = true;

                return result;
            });
        },
        getBookList: function () {

            var bookListContainers = document.getElementsByClassName('booklist-container');

            for (var i=0; i < bookListContainers.length; i++){
                var container = bookListContainers[i];
                var data = container.getElementsByTagName('input')[0].value;

                if (data != undefined){
                    var arr = data.split(',');
                    console.log(data);

                    for(var j=0; j < arr.length; j++) {
                        var isbn = arr[j];
                        var url = "https://www.googleapis.com/books/v1/volumes?q=isbn:";
                        url = url.concat(isbn);

                        myBookList.getUrl(url).then(myBookList.populatePlusletView.bind(null, container, isbn), function(error) {
                            console.error("Failed!", error);
                        });
                    }}
            }
        },
        populatePlusletView: function(container, isbn, response){
            var obj = $.parseJSON( response );

            if (obj.totalItems != 0){
                obj = obj.items[0];

                var br = document.createElement('br');
                var divContent = document.createElement('div');

                var syndeticCode = container.getElementsByTagName('input')[1].value;
                myBookList.setBookCover(isbn, syndeticCode, obj.volumeInfo, container);

                var bookTitle = obj.volumeInfo.title;
                var titleHeader = document.createElement('h2');
                titleHeader.setAttribute('data-book-title', bookTitle)
                titleHeader.innerHTML = bookTitle;
                titleHeader.appendChild(br);
                divContent.appendChild(titleHeader);

                var authorsList = obj.volumeInfo.authors;
                var authorsListP = document.createElement('p');
                var authorsListInfo = authorsList.join(", ");
                authorsListP.setAttribute('data-book-author', authorsListInfo);
                authorsListP.innerHTML = authorsListInfo;
                authorsListP.appendChild(br);
                divContent.appendChild(authorsListP);

                var date = myBookList.formatDate(obj.volumeInfo.publishedDate);
                var publishedDate = document.createElement('p');
                publishedDate.setAttribute('data-book-pubdate', date);
                publishedDate.innerHTML = date;
                publishedDate.appendChild(br);
                divContent.appendChild(publishedDate);

                myBookList.setBookISBNNumber(obj, isbn, divContent);

                var divBook = document.createElement('div');
                divBook.setAttribute('data-book-id', isbn);
                divBook.appendChild(divContent);
                container.appendChild(divBook);

            }else{
                myBookList.setNumberErrorMessage(isbn, container);
            }
        },
        setBookISBNNumber: function (info, isbn, container){

            var industryIdentifiers = info.volumeInfo.industryIdentifiers;
            var isbnNumberList = document.createElement('ul');

            for(var i = 0; i < industryIdentifiers.length; i++){

                var li = document.createElement('li');

                if (industryIdentifiers[i].type === 'ISBN_10'){
                    li.innerHTML = 'ISBN10: ' + industryIdentifiers[i].identifier;
                }else if (industryIdentifiers[i].type === 'ISBN_13'){
                    li.innerHTML = 'ISBN13: ' + industryIdentifiers[i].identifier;
                }else{
                    li.innerHTML = industryIdentifiers[i].identifier;
                }

                isbnNumberList.appendChild(li);
            }

            isbnNumberList.appendChild(document.createElement('br'));
            isbnNumberList.setAttribute('data-book-isbn-list', isbn);
            container.appendChild(isbnNumberList);
        },
        setNumberErrorMessage: function (isbn, container) {
            var checkNumberMessage = document.createElement('h2');
            checkNumberMessage.innerHTML = "Please check this number: " + isbn;
            var divBook = document.createElement('div');
            divBook.appendChild(checkNumberMessage);
            container.appendChild(divBook);
        },
        setBookCover: function (isbn, syndeticsClientCode, info, container) {
            var foundInSyndetics = false;
            var foundInGoogle = false;
            var testingExist = false;

            if (!(syndeticsClientCode === "")) {
                $.ajax({
                    type: "GET",
                    url: '../../subjects/includes/syndetics_image_xml_data.php',
                    data: {
                        "isbn": isbn,
                        "syndeticsClientCode": syndeticsClientCode
                    },
                    dataType: "text",
                    async: false,
                    success: function(data) {
                        if (data.localeCompare('false') != 0){
                            foundInSyndetics = true;
                            myBookList.setBookCoverSrc(container, data);
                        }
                    }
                });
            }

            if (!foundInSyndetics){
                var googleImageUrl;
                var test = info.hasOwnProperty('imageLinks');

                    if (test){
                        googleImageUrl = info.imageLinks.thumbnail;
                    }

                if (googleImageUrl != null){
                    foundInGoogle = true;
                    myBookList.setBookCoverSrc(container, googleImageUrl);
                }
            }

            if (!foundInGoogle){
                myBookList.setBookCoverSrc(container, "");
            }
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