/* jQuery.head - v1.0.3 - K Reeve aka BinaryKitten
*
*	makes a Head Request via XMLHttpRequest (ajax) and returns an object/array of headers returned from the server
*	$.head(url, [data], [callback])
*		url			The url to which to place the head request
*		data		(optional) any data you wish to pass - see $.post and $.get for more info
*		callback	(optional) Function to call when the head request is complete.
*					This function will be passed an object containing the headers with
*					the object consisting of key/value pairs where the key is the header name and the
*					value it's corresponding value
*
*	for discussion and info please visit: http://binarykitten.me.uk/jQHead
*
* ------------ Version History -----------------------------------
* v1.0.3
* 	Fixed the zero-index issue with the for loop for the headers
* v1.0.2
* 	placed the function inside an enclosure
*
* v1.0.1
* 	The 1st version - based on $.post/$.get
*/

(function ($) {
  $.extend({
	head: function( url, data, callback ) {
	  if ( $.isFunction( data ) ) {
		  callback = data;
		  data = {};
	  }

	  return $.ajax({
		type: "HEAD",
		url: url,
		data: data,
		complete: function (XMLHttpRequest, textStatus) {
		  var headers = XMLHttpRequest.getAllResponseHeaders().split("\n");
		  var new_headers = {};
		  var l = headers.length;
		  for (var key=0;key<l;key++) {
			  if (headers[key].length != 0) {
				  header = headers[key].split(": ");
				  new_headers[header[0]] = header[1];
			  }
		  }
		  if ($.isFunction(callback)) {
			callback(new_headers);
		  }
		}
	  });
	}
  });
})(jQuery);
