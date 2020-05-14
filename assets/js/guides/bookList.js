function bookList() {
  var validGoogleBooksAPIKey = false;
  var validSyndeticsClientCode = false;
  var urlPrefix = "";
  var view = "control";

  const isbnRegExp = /[^0-9,x,-]/gi;

  var validation = {
    validateSyndeticsClientCode: function () {
      $.ajax({
        url: "bookListPlusletActionRouter.php",
        data: {
          action: "validateSyndeticsClientCode",
        },
        async: false,
        success: function (result) {
          validSyndeticsClientCode = result;
        },
      });
    },
    validateGoogleBooksAPIKey: function () {
      $.ajax({
        url: "bookListPlusletActionRouter.php",
        data: {
          action: "validateGoogleBooksAPIKey",
        },
        async: false,
        success: function (result) {
          validGoogleBooksAPIKey = result;
        },
      });
    },
  };

  var myBookList = {
    bindUiActions: function (container) {
      myBookList.getBookList(container);
    },
    bindUiActionsForEditView: function () {
      myBookList.validCharacters();
			myBookList.isNumberKey();
			
			// Add ISBN button
			myBookList.addIsbnButtonListener();
			
			// Delete ISBN buttons
			myBookList.deleteIsbnButtonListener();
    },
    init: function (container) {
      myBookList.getUrlPrefix();
      var url = document.location.href;
      if (url.indexOf("control") === -1) {
        view = "live";
      }

      $.when(
        validation.validateGoogleBooksAPIKey(),
        validation.validateSyndeticsClientCode()
      ).then(function (a1, a2) {
        myBookList.bindUiActions(container);
      });
    },
    initEditView: function () {
      myBookList.bindUiActionsForEditView();
      myBookList.makeListSortable();
    },
    scrubInput: function (inputString) {
      let scrubbedString;
      
      if (typeof inputString === 'string'){
        scrubbedString = inputString.replace(isbnRegExp, '');
      };
      
      return scrubbedString;
    },
    validCharacters: function () {
      $("input[name=isbn-input]").on("paste", ()=> {
        var $el = $(this);
        setTimeout(()=> {
          $el.val((i, val)=> {
            return this.scrubInput(val);
          });
        });
      });
    },
    isNumberKey: function () {
      $("input[name=isbn-input]").keydown(function (evt) {
        var result = true;
        var charCode = evt.which ? evt.which : evt.keyCode;
        if (charCode < 48 || charCode > 57) result = false;

        if (charCode == 88 || charCode == 44 || charCode == 8) result = true;

        if (
          (evt.ctrlKey || evt.metaKey) &&
          (charCode == 86 || charCode == 67 || charCode == 90 || charCode == 88)
        ) {
          //for ctrl key and for Mac's keyboards command key
          result = true;
        }

        if (evt.shiftKey && 37 <= charCode && charCode <= 40) {
          // shift key
          result = true;
        }

        if (
          (37 <= charCode && charCode <= 40) ||
          (96 <= charCode && charCode <= 105)
        ) {
          //keypad numbers
          result = true;
        }

        if (charCode == 109 || charCode == 189) {
          // - key
          result = true;
        }

        return result;
      });
    },
    addIsbnButtonListener: function () {
			$('button[name=add-isbn]').on('click', (clickEvent)=> {
        const theButton = $(clickEvent.currentTarget);
        const inputField = $(theButton).prev('input');
        const isbn = $(inputField).val();
        
        // Don't run if empty string
        if (isbn === '') {
          return;
        };

        const validLengths = [10, 13];
        const filteredValue = this.scrubInput(isbn);
        const isbnLengthNotValid = !( validLengths.includes(filteredValue.length) );

				// Make sure ISBN is 10 or 13 digits
				if (isbnLengthNotValid) {
					console.error('isbn.length incorrect, isbn.length was: ', isbn.length);
					return false;
				};

        this.addIsbnToList(isbn);

        // Clear input element
        $(inputField).val('');

        // Synchronize invisible textarea with updated list
        this.synchronizeTextarea();
			});
		},
		addIsbnToList: function (isbn) {
			const newLi = this.getSortableIsbnLi(isbn);
			$('.booklist-draggables-container').append(newLi);
			
			// Restripe all the rows
			this.stripeRows();

			// Recreate event listeners for all delete buttons, so new row also gets it
      this.deleteIsbnButtonListener();
		},
		deleteIsbnButtonListener: function () {
			// Clear all delete listeners to start, so newly added ISBNs are added correctly
			$('.booklist-delete-button').unbind('click');

			$('.booklist-delete-button').on('click', (event)=> {
				const whichLi = $(event.currentTarget).closest('li');
        this.deleteIsbnFromList(whichLi);

        // Synchronize invisible textarea with updated list
        this.synchronizeTextarea();

        // Re-stripe the list
        this.stripeRows();
			});
		},
		deleteIsbnFromList: function (liToDelete) {
			$(liToDelete).remove();
		},
    populatePlusletViewFromCache: function (
      container,
      response,
      coverPath,
      contentDivNumber
    ) {
      var data = response.isbn;

      if (Object.prototype.toString.call(data) === "[object Array]") {
        var text =
          '{ "title":"' +
          data[0].title +
          '" , "author":"' +
          data[1].author +
          '",' +
          '"date":"' +
          data[2].date +
          '" , "primoUrl":"' +
          data[3].primoUrl +
          '" }';

        var obj = JSON.parse(text);
        data = obj;
      }
      myBookList.insertBookInformation(
        data,
        container,
        coverPath,
        contentDivNumber
      );
    },
    insertBookInformation: function (
      data,
      container,
      coverPath,
      contentDivNumber
    ) {
      var br = document.createElement("br");
      var divContent = $(container).find(
        "[content-div-number='" + contentDivNumber + "']"
      )[0];
      var divData = document.createElement("div");
      divData.classList.add("booklist_isbn_data");
			var anchorUrl = $("<textarea/>").html(data.primoUrl).text();

      myBookList.setBookCoverSrc(
        container,
        coverPath,
        anchorUrl,
        contentDivNumber
      );

      var bookTitle = data.title;
      var titleHeader = document.createElement("h4");
      titleHeader.setAttribute("data-book-title", bookTitle);
      titleHeader.innerHTML = bookTitle;
      titleHeader.appendChild(br);

      if (anchorUrl) {
        var anchor = document.createElement("a");
        anchor.setAttribute("href", anchorUrl);
        anchor.setAttribute("target", "_blank");
        anchor.appendChild(titleHeader);
        divData.appendChild(anchor);
      } else {
        divData.appendChild(titleHeader);
      }

      var br = document.createElement("br");

      if (data.author) {
        var authorsList = data.author;
        var authorsListP = document.createElement("p");
        authorsListP.classList.add("booklist-author");
        authorsListP.setAttribute("data-book-author", authorsList);
        authorsListP.innerHTML = authorsList;
        authorsListP.appendChild(br);
        divData.appendChild(authorsListP);
      }

      var date = data.date;
      var publishedDate = document.createElement("p");
      publishedDate.classList.add("booklist-date");
      publishedDate.setAttribute("data-book-pubdate", date);
      publishedDate.innerHTML = date;
      publishedDate.appendChild(br);
      divData.appendChild(publishedDate);

      divContent.appendChild(divData);
    },
    getBookList: function (container) {
      var data = container.getElementsByTagName("input")[0].value;
      var regex = new RegExp(
        "(ISBN[-]*(1[03])*[ ]*(: ){0,1})*(([0-9Xx][- ]*){13}|([0-9Xx][- ]*){10})"
      );
      var prefix = urlPrefix;

      if (data != undefined) {
        if (data.trim()) {
          var arr = data.split(",");

          for (var j = 0; j < arr.length; j++) {
            var isbn = arr[j];
            var divContent = document.createElement("div");
            divContent.classList.add("booklist_item");
            divContent.setAttribute("content-div-number", j);
            container.appendChild(divContent);
            if (isbn.trim()) {
              if (regex.test(isbn)) {
                myBookList.processISBN(isbn, prefix, container, j);
              } else {
                if (view === "control") {
                  myBookList.setNumberErrorMessage(isbn, container, j);
                }
              }
            }
          }
        }
      }
    },
    processISBN: function (isbn, prefix, container, contentDivNumber) {
      $.when(
        myBookList.isFileInCache(
          prefix + "assets/cache/" + isbn + ".bookmetadata",
          "GET"
        ),
        myBookList.isFileInCache(prefix + "assets/cache/" + isbn + ".jpg"),
        "HEAD"
      ).then(
        function (a1, a2) {
          var dataResponse = a1[0];
          dataResponse = $.parseJSON(dataResponse);
          myBookList.populatePlusletViewFromCache(
            container,
            dataResponse,
            prefix + "assets/cache/" + isbn + ".jpg",
            contentDivNumber
          );
        },
        function () {
          myBookList.updateBookCache(container, isbn, contentDivNumber);
        }
      );
    },
    isFileInCache: function (url, type) {
      return $.ajax({
        type: type,
        url: url,
      });
    },
    getUrlPrefix: function () {
      var pathName = window.location.pathname;
      var protocol = window.location.protocol;
      var host = window.location.host;

      if (pathName.indexOf("control") !== -1) {
        pathName = pathName.split("control")[0];
      } else if (pathName.indexOf("subjects") !== -1) {
        pathName = pathName.split("subjects")[0];
      }
      urlPrefix = protocol + "//" + host + pathName;
    },
    getCoverPathFromSyndetics: function (isbn) {
      var result = "";
      $.ajax({
        url: "bookListPlusletActionRouter.php",
        data: {
          action: "validateSyndeticsImageExists",
          isbn: isbn,
        },
        async: false,
        success: function (data) {
          if (data.trim()) {
            result = data;
          }
        },
      });

      return result;
    },
    updateBookCache: function (container, isbn, contentDivNumber) {
      var coverPath = "";
      var coverPathSyndetics = "";
      var url = "";
      var result = { isbn: [] };
      var googleBooksAPIKey = container.getElementsByTagName("input")[1].value;

      $.ajax({
        url: "bookListPlusletActionRouter.php",
        data: {
          action: "isbnPrimoCheck",
          isbn: isbn,
        },
        success: function (anchorUrl) {
          if (validSyndeticsClientCode === "true") {
            coverPath = myBookList.getCoverPathFromSyndetics(isbn);
            coverPathSyndetics = coverPath;
          }

          if (validGoogleBooksAPIKey === "true") {
            var preparedISBNForGoogle = isbn.replace(/[^0-9X]/g, "");
            url =
              "https://www.googleapis.com/books/v1/volumes?key=" +
              googleBooksAPIKey +
              "&q=isbn:" +
              preparedISBNForGoogle;
            $.ajax({
              type: "GET",
              url: url,
              statusCode: {
                403: function () {
                  if (view === "control") {
                    myBookList.setNoSourceAvailableErrorMessage(
                      isbn,
                      container,
                      contentDivNumber
                    );
                  }
                },
              },
              success: function (data) {
                var obj = data;
                if (obj.totalItems != 0) {
                  obj = obj.items[0];
                  result.isbn.push({
                    title: obj.volumeInfo.title,
                  });
                  var authors = "";
                  if (obj.volumeInfo.authors) {
                    authors = obj.volumeInfo.authors.join(", ");
                  }
                  result.isbn.push({
                    author: authors,
                  });
                  result.isbn.push({
                    date: myBookList.formatDate(obj.volumeInfo.publishedDate),
                  });
                  result.isbn.push({
                    primoUrl: anchorUrl,
                  });

                  if (!coverPathSyndetics) {
                    if (typeof obj.volumeInfo.imageLinks !== "undefined") {
                      coverPath = obj.volumeInfo.imageLinks.thumbnail;
                    } else {
                      coverPath = urlPrefix + "assets/images/blank-cover.png";
                    }
                  }

                  myBookList.populatePlusletViewFromCache(
                    container,
                    result,
                    coverPath,
                    contentDivNumber
                  );
                  myBookList.downloadCoverToCache(coverPath, isbn);
                  myBookList.downloadDataToCache(result, isbn);
                } else {
                  myBookList.getMetaDataFromOpenLibrary(
                    container,
                    isbn,
                    coverPathSyndetics,
                    anchorUrl,
                    contentDivNumber
                  );
                }
              },
            });
          } else {
            myBookList.getMetaDataFromOpenLibrary(
              container,
              isbn,
              coverPathSyndetics,
              anchorUrl,
              contentDivNumber
            );
          }
        },
      });
    },
    getBookDataFromOpenLibrary: function (isbn) {
      return $.ajax({
        type: "GET",
        url: "bookListPlusletActionRouter.php",
        dataType: "text",
        data: {
          isbn: isbn,
          action: "bookMetadataFromOpenLibrary",
        },
      });
    },
    getBookCoverFromOpenLibrary: function (isbn) {
      return $.ajax({
        type: "GET",
        url: "bookListPlusletActionRouter.php",
        dataType: "text",
        data: {
          isbn: isbn,
          action: "bookCoverFromOpenLibrary",
        },
      });
    },
    getMetaDataFromOpenLibrary: function (
      container,
      isbn,
      coverPathSyndetics,
      primoUrl,
      contentDivNumber
    ) {
      var coverPath = "";
      var validOpenLibraryData = false;
      var validSyndetics = false;
      var result = { isbn: [] };
      var succeed = false;

      $.when(
        myBookList.getBookDataFromOpenLibrary(isbn),
        myBookList.getBookCoverFromOpenLibrary(isbn)
      ).then(function (a1, a2) {
        if (!coverPathSyndetics.trim()) {
          if (a2[1] === "success") {
            coverPath = a2[0];
          }
        } else {
          if (coverPathSyndetics.indexOf("blank-cover") === -1) {
            validSyndetics = true;
          } else {
            if (a2[1] === "success") {
              coverPath = a2[0];
            }
          }
        }

        if (a1[1] === "success") {
          var obj = $.parseJSON(a1[0]);
          if (obj.isbn.length != 0) {
            validOpenLibraryData = true;
            obj = obj.isbn;

            result.isbn.push({
              title: obj.title,
            });
            result.isbn.push({
              author: obj.author.join(", "),
            });
            result.isbn.push({
              date: obj.date !== "" ? myBookList.formatDate(obj.date) : "",
            });
            result.isbn.push({
              primoUrl: primoUrl,
            });
          }
        }

        if (validSyndetics && validOpenLibraryData) {
          myBookList.populatePlusletViewFromCache(
            container,
            result,
            coverPathSyndetics,
            contentDivNumber
          );
          myBookList.downloadCoverToCache(coverPathSyndetics, isbn);
          myBookList.downloadDataToCache(result, isbn);
          succeed = true;
        } else if (validOpenLibraryData) {
          myBookList.populatePlusletViewFromCache(
            container,
            result,
            coverPath,
            contentDivNumber
          );
          myBookList.downloadCoverToCache(coverPath, isbn);
          myBookList.downloadDataToCache(result, isbn);
          succeed = true;
        }

        if (!validOpenLibraryData && view === "control") {
          myBookList.setNoSourceAvailableErrorMessage(
            isbn,
            container,
            contentDivNumber
          );
        }
      });
    },
    formatAuthorsFromOpenLibrary: function (authors) {
      var names = Array();

      for (var name in authors) {
        names.push(authors);
      }

      return names.join(", ");
    },
    downloadDataToCache: function (data, isbn) {
      $.ajax({
        type: "GET",
        url: "bookListPlusletActionRouter.php",
        data: {
          title: data.isbn[0].title,
          isbn: isbn,
          author: data.isbn[1].author,
          date: data.isbn[2].date,
          primoUrl: data.isbn[3].primoUrl,
          action: "bookMetadataDownload",
        },
        dataType: "text",
      });
    },
    downloadCoverToCache: function (url, isbn) {
      $.ajax({
        type: "GET",
        url: "bookListPlusletActionRouter.php",
        data: {
          url: url,
          isbn: isbn,
          action: "bookCoverDownload",
        },
        dataType: "text",
      });
    },
    setNumberErrorMessage: function (isbn, container, contentDivNumber) {
      var checkNumberMessage = document.createElement("p");
      checkNumberMessage.innerHTML = "Please check this number: " + isbn;
      var divBook = $(container).find(
        "[content-div-number='" + contentDivNumber + "']"
      )[0];
      divBook.classList.add("booklist-alert");
      divBook.appendChild(checkNumberMessage);
    },
    setNoSourceAvailableErrorMessage: function (
      isbn,
      container,
      contentDivNumber
    ) {
      var checkNumberMessage = document.createElement("p");
      checkNumberMessage.innerHTML = "Sorry, this ISBN can't be found: " + isbn;
      var divBook = $(container).find(
        "[content-div-number='" + contentDivNumber + "']"
      )[0];
      divBook.classList.add("booklist-alert");
      divBook.appendChild(checkNumberMessage);
    },
    setBookCoverSrc: function (container, url, anchorUrl, contentDivNumber) {
      var imgCover = document.createElement("img");
      imgCover.setAttribute("src", url);
      imgCover.setAttribute("data-show-image", url);

      var divCover = document.createElement("div");
      divCover.classList.add("booklist_isbn_cover");

      if (anchorUrl) {
        var anchor = document.createElement("a");
        anchor.setAttribute("href", anchorUrl);
        anchor.setAttribute("target", "_blank");

        anchor.appendChild(imgCover);
        divCover.appendChild(anchor);
      } else {
        divCover.appendChild(imgCover);
      }

      var bookItem = $(container).find(
        "[content-div-number='" + contentDivNumber + "']"
      )[0];
      bookItem.appendChild(divCover);
    },
    getUrl: function (url) {
      // Return a new promise.
      return new Promise(function (resolve, reject) {
        // Do the usual XHR stuff
        var req = new XMLHttpRequest();
        req.open("GET", url);

        req.onload = function () {
          // This is called even on 404 etc
          // so check the status
          if (req.status == 200) {
            // Resolve the promise with the response text
            resolve(req.response);
          } else {
            // Otherwise reject with the status text
            // which will hopefully be a meaningful error
            reject(Error(req.statusText));
          }
        };

        // Handle network errors
        req.onerror = function () {
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
    },
    makeListSortable: function () {
      // Make whole Book List <ul> sortable via jQuery
      $(".booklist-draggables-container").sortable({
				update: (event, ui)=> {
					this.onListChange(event, ui);
				},
				change: (event, ui)=> {
					this.onListChange(event, ui);
				},
				start: (event, ui)=> {
					this.onListChange(event, ui);
				}
			});

			this.stripeRows();
		},
		onListChange: function(event, ui) {
			console.warn('HITTING ONSORTSTOP()');
			console.warn('event: ', event.type);

			switch(event.type) {
				case 'sortstart':
					break;
				case 'sortchange':
					break;
				case 'sortupdate':
          this.synchronizeTextarea();
					this.stripeRows();
					break;
			};
    },
    synchronizeTextarea: function () {
      const currentListJoined = this.getBooklistFromSortables().join(',');
      const textarea = $("textarea[name='BookList-extra-isbn']");

      if (currentListJoined.length) {
        $(textarea).html(currentListJoined);
      };
    },
    getBooklistFromSortables: function () {
      const containerDiv = $('.booklist-draggables-container');
      const sortableItems = containerDiv.children();

      return $.map(sortableItems, (item)=> $(item).data('isbn'));
    },
		stripeRows: function() {
			const mapping = {
				evenRows: "evenrow striper",
				oddRows: "oddrow striper"
			};

			const rows = $('.booklist-draggables-container li');

			$.each(rows, (index, element)=> {
				// Strip off existing classes
				$(element).removeClass('evenrow oddrow striper');

				// Even indices
				if ((index + 1) % 2 === 0) {
					$(element).addClass(mapping.evenRows);
				} else {
					$(element).addClass(mapping.oddRows);		
				};
			});
		},
		getSortableIsbnLi: function(isbn) {
      // NOTE: Must be kept in sync with template in
      // BookListEditOutput.php
			return `
				<li
					data-isbn='${isbn}'
					class='booklist-item-draggable'>
            <div>
              <i class='fa fa-bars'></i>
              <span class='isbn-number'> ${isbn}</span>
            </div>
						<i class='fa fa-trash booklist-delete-button' data-isbn='${isbn}'></i>
				</li>
			`;
		}
  };

  return myBookList;
}
