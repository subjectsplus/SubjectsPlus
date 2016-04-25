  //Misc features needed for perform the actions
  var UTILS = UTILS || {};
  UTILS.generateISBNId = function(){
  	var temp_date = new Date();  //this line avoid the creation of a new Date instance every time the function is called. The function is called only one time and returns a closure to access to the temp_date variable.
  	return function(){
        return temp_date.valueOf();
    }
  }
//@todo 
//-create an html helper to put the above code inside, this field is not responsible
//for that code format
//-determine a way that ISBNActions.init() is called only one time, so the click events over the buttons are only declared one time.
  var ISBNActions = ISBNActions || {
        init : function () {
                     $(document).on("click","#isbn_add_new", function(){
                      var id = (UTILS.generateISBNId())();
                      
                      var data = {id:id};
                      $("#isbn_input_container").append(ISBNActions.getISBNInputBody(data));
                      });
                      $(document).on("click",".check_isbn", function(){
                        var input_to_check = $(this).siblings(".isbn_input").val();
                        var ajax_request = $.ajax({
                                             method: "POST",
                                             url: "helpers/isbn_search/isbn_checker.php",
                                             data: {isbn_number : input_to_check}
                                           });
                        ajax_request.done(function( msg ) {
                        	var json = JSON.parse(msg);
                            alert(json.totalItems);
                          });                    
                      });
                      $(document).on("click",".delete_isbn_entry", function(){
                         var $_this = $(this);
                         var id_to_delete = $_this.attr("delete-container-id");
                         $("#"+id_to_delete).remove();
                      });
            },
        getISBNInputBody : function(params) {
          var insert_text_in =  '<div id="isbn'+ params.id +'" class="single_isbn">'+ 
                                    '<input class="isbn_input" name="ISBNSearch-extra-isbn'+ params.id +'" type="text"/>'+
                                    '<br>'+
                                    '<button class="check_isbn">check</button>'+
                                    '<button class="delete_isbn_entry" delete-container-id = "isbn'+ params.id +'">-</button>'+
                                '</div>';
          return insert_text_in;
        }    
      }
   
  ISBNActions.init();

