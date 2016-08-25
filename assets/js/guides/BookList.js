function bookList() {

    var myBookList = {

        settings: {},
        strings: {},
        bindUiActions: function () {
            myBookList.validCharacters();
            myBookList.isNumberKey();
            myBookList.getBookList();
            myBookList.formatDate();
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
                var result = true;
                var charCode = (evt.which) ? evt.which : event.keyCode;
                if (charCode < 48 || charCode > 57)
                    result = false;

                if (charCode == 88 || charCode == 44 || charCode == 32)
                    result = true;

                return result;
            });
        },
        getBookList: function () {

            var data = $('#isbnList').val();
            console.log(data);
            if (data != undefined){
             var arr = data.split(',');
                console.log(data);

            for(var i=0; i < arr.length; i++) {
                var isbn = arr[i];
                var url = "https://www.googleapis.com/books/v1/volumes?q=isbn:";
                url = url.concat(isbn);

                myBookList.getUrl(url).then(function(response) {
                    var obj = $.parseJSON( response );
                    var container = document.getElementById ("container");

                    if (obj.totalItems != 0){

                        var authorsList = obj.items[0].volumeInfo.authors;
                        var bookId = obj.items[0].id;
                        console.log(authorsList.join(", "));

                        var br = document.createElement('br');
                        var imgCover = document.createElement('img');
                        imgCover.setAttribute('src', obj.items[0].volumeInfo.imageLinks.thumbnail);

                        var divCover = document.createElement('div');
                        divCover.appendChild(imgCover);

                        var titleHeader = document.createElement('h2');
                        var titleHeaderId = 'titleHeader' + bookId;
                        titleHeader.setAttribute('id', titleHeaderId);
                        titleHeader.appendChild(br);

                        var isbnNumber = document.createElement('h3');
                        var isbnNumberId = 'isbnNumber' + bookId;
                        isbnNumber.setAttribute('id', isbnNumberId);
                        isbnNumber.appendChild(br);

                        var authorsListP = document.createElement('p');
                        var authorListPId = 'authorsListP' + bookId;
                        authorsListP.setAttribute('id', authorListPId);
                        authorsListP.appendChild(br);

                        var publishedDate = document.createElement('p');
                        var publishedDateId = 'publishedDate' + bookId;
                        publishedDate.setAttribute('id', publishedDateId);
                        publishedDate.appendChild(br);

                        var divContent = document.createElement('div');
                        divContent.appendChild(titleHeader);
                        divContent.appendChild(isbnNumber);
                        divContent.appendChild(authorsListP);
                        divContent.appendChild(publishedDate);

                        var divBook = document.createElement('div');
                        divBook.appendChild(divCover);
                        divBook.appendChild(divContent);
                        container.appendChild(divBook);

                        $("#"+titleHeaderId).html(obj.items[0].volumeInfo.title);
                        $("#"+isbnNumberId).html("ISBN: " + obj.items[0].volumeInfo.industryIdentifiers[1].identifier);
                        $("#"+authorListPId).html(authorsList.join(", "));
                        $("#"+publishedDateId).html(myBookList.formatDate(obj.items[0].volumeInfo.publishedDate));

                    }else{
                        var checkNumberMessage = document.createElement('h2');
                        var checkNumberMessageId = 'checkNumberMessage' + isbn;
                        checkNumberMessage.setAttribute('id', checkNumberMessageId);
                        var divBook = document.createElement('div');
                        divBook.appendChild(checkNumberMessage);
                        container.appendChild(divBook);
                        $("#"+checkNumberMessageId).html("Please check this number: " + isbn);
                    }

                }, function(error) {
                    console.error("Failed!", error);
                });
            }}
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
            var monthNames = ["January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"
            ];

            return monthNames[newDate.getMonth()] + " " + newDate.getDate() + ", " + newDate.getFullYear();

        }
    };

    return myBookList;
}