(self["webpackChunksubjectsplus"] = self["webpackChunksubjectsplus"] || []).push([["patron_database_list"],{

/***/ "./assets/javascripts/autocomplete.js":
/*!********************************************!*\
  !*** ./assets/javascripts/autocomplete.js ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "createAutocomplete": () => (/* binding */ createAutocomplete)
/* harmony export */ });
/* harmony import */ var core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/es.object.to-string.js */ "./node_modules/core-js/modules/es.object.to-string.js");
/* harmony import */ var core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var core_js_modules_es_promise_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! core-js/modules/es.promise.js */ "./node_modules/core-js/modules/es.promise.js");
/* harmony import */ var core_js_modules_es_promise_js__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_promise_js__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var accessible_autocomplete__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! accessible-autocomplete */ "./node_modules/accessible-autocomplete/dist/accessible-autocomplete.min.js");
/* harmony import */ var accessible_autocomplete__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(accessible_autocomplete__WEBPACK_IMPORTED_MODULE_2__);



function createAutocomplete(selector, id, apiEndpoint, name, templates) {
  var onConfirmCallback = arguments.length > 5 && arguments[5] !== undefined ? arguments[5] : function (q, p) {};
  accessible_autocomplete__WEBPACK_IMPORTED_MODULE_2___default()({
    element: document.querySelector(selector),
    id: id,
    showNoOptionsFound: false,
    source: getSuggestCallback(apiEndpoint),
    name: name,
    templates: templates,
    onConfirm: onConfirmCallback
  });
}

function getSuggestCallback(apiEndpoint) {
  return function suggest(query, populateResults) {
    fetch(apiEndpoint + query).then(function (response) {
      return response.json();
    }).then(function (data) {
      return populateResults(data);
    });
  };
}

/***/ }),

/***/ "./assets/javascripts/patron/database_list.js":
/*!****************************************************!*\
  !*** ./assets/javascripts/patron/database_list.js ***!
  \****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _autocomplete__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../autocomplete */ "./assets/javascripts/autocomplete.js");


function goToDatabase(result) {
  window.location.href = result['url'];
}

function template(result) {
  return result && result['title'];
}

(0,_autocomplete__WEBPACK_IMPORTED_MODULE_0__.createAutocomplete)('#my-autocomplete-container', 'my-autocomplete', '/api/autocomplete/databases.json?query=', 'searchterm', {
  inputValue: template,
  suggestion: template
}, goToDatabase);

/***/ })

},
/******/ __webpack_require__ => { // webpackRuntimeModules
/******/ "use strict";
/******/ 
/******/ var __webpack_exec__ = (moduleId) => (__webpack_require__(__webpack_require__.s = moduleId))
/******/ __webpack_require__.O(0, ["vendors-node_modules_core-js_modules_es_object_to-string_js-node_modules_core-js_modules_es_p-2a1352","vendors-node_modules_accessible-autocomplete_dist_accessible-autocomplete_min_js"], () => (__webpack_exec__("./assets/javascripts/patron/database_list.js")));
/******/ var __webpack_exports__ = __webpack_require__.O();
/******/ }
]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly9zdWJqZWN0c3BsdXMvLi9hc3NldHMvamF2YXNjcmlwdHMvYXV0b2NvbXBsZXRlLmpzIiwid2VicGFjazovL3N1YmplY3RzcGx1cy8uL2Fzc2V0cy9qYXZhc2NyaXB0cy9wYXRyb24vZGF0YWJhc2VfbGlzdC5qcyJdLCJuYW1lcyI6WyJjcmVhdGVBdXRvY29tcGxldGUiLCJzZWxlY3RvciIsImlkIiwiYXBpRW5kcG9pbnQiLCJuYW1lIiwidGVtcGxhdGVzIiwib25Db25maXJtQ2FsbGJhY2siLCJxIiwicCIsImFjY2Vzc2libGVBdXRvY29tcGxldGUiLCJlbGVtZW50IiwiZG9jdW1lbnQiLCJxdWVyeVNlbGVjdG9yIiwic2hvd05vT3B0aW9uc0ZvdW5kIiwic291cmNlIiwiZ2V0U3VnZ2VzdENhbGxiYWNrIiwib25Db25maXJtIiwic3VnZ2VzdCIsInF1ZXJ5IiwicG9wdWxhdGVSZXN1bHRzIiwiZmV0Y2giLCJ0aGVuIiwicmVzcG9uc2UiLCJqc29uIiwiZGF0YSIsImdvVG9EYXRhYmFzZSIsInJlc3VsdCIsIndpbmRvdyIsImxvY2F0aW9uIiwiaHJlZiIsInRlbXBsYXRlIiwiaW5wdXRWYWx1ZSIsInN1Z2dlc3Rpb24iXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7OztBQUFBO0FBRU8sU0FBU0Esa0JBQVQsQ0FDSEMsUUFERyxFQUVIQyxFQUZHLEVBR0hDLFdBSEcsRUFJSEMsSUFKRyxFQUtIQyxTQUxHLEVBT0w7QUFBQSxNQURFQyxpQkFDRix1RUFEc0IsVUFBQ0MsQ0FBRCxFQUFJQyxDQUFKLEVBQVUsQ0FBRSxDQUNsQztBQUNFQyxnRUFBc0IsQ0FBQztBQUNuQkMsV0FBTyxFQUFFQyxRQUFRLENBQUNDLGFBQVQsQ0FBdUJYLFFBQXZCLENBRFU7QUFFbkJDLE1BQUUsRUFBRUEsRUFGZTtBQUduQlcsc0JBQWtCLEVBQUUsS0FIRDtBQUluQkMsVUFBTSxFQUFFQyxrQkFBa0IsQ0FBQ1osV0FBRCxDQUpQO0FBS25CQyxRQUFJLEVBQUVBLElBTGE7QUFNbkJDLGFBQVMsRUFBRUEsU0FOUTtBQU9uQlcsYUFBUyxFQUFFVjtBQVBRLEdBQUQsQ0FBdEI7QUFTSDs7QUFFRCxTQUFTUyxrQkFBVCxDQUE0QlosV0FBNUIsRUFBeUM7QUFDckMsU0FBTyxTQUFTYyxPQUFULENBQWtCQyxLQUFsQixFQUF5QkMsZUFBekIsRUFBMEM7QUFDN0NDLFNBQUssQ0FBQ2pCLFdBQVcsR0FBQ2UsS0FBYixDQUFMLENBQ0NHLElBREQsQ0FDTSxVQUFBQyxRQUFRO0FBQUEsYUFBSUEsUUFBUSxDQUFDQyxJQUFULEVBQUo7QUFBQSxLQURkLEVBRUNGLElBRkQsQ0FFTSxVQUFBRyxJQUFJO0FBQUEsYUFBSUwsZUFBZSxDQUFDSyxJQUFELENBQW5CO0FBQUEsS0FGVjtBQUlILEdBTEQ7QUFNSCxDOzs7Ozs7Ozs7Ozs7O0FDNUJEOztBQUVBLFNBQVNDLFlBQVQsQ0FBc0JDLE1BQXRCLEVBQThCO0FBQzVCQyxRQUFNLENBQUNDLFFBQVAsQ0FBZ0JDLElBQWhCLEdBQXVCSCxNQUFNLENBQUMsS0FBRCxDQUE3QjtBQUNEOztBQUVELFNBQVNJLFFBQVQsQ0FBa0JKLE1BQWxCLEVBQTBCO0FBQ3hCLFNBQU9BLE1BQU0sSUFBSUEsTUFBTSxDQUFDLE9BQUQsQ0FBdkI7QUFDRDs7QUFFRDFCLGlFQUFrQixDQUNoQiw0QkFEZ0IsRUFFaEIsaUJBRmdCLEVBR2hCLHlDQUhnQixFQUloQixZQUpnQixFQUtoQjtBQUNFK0IsWUFBVSxFQUFFRCxRQURkO0FBRUVFLFlBQVUsRUFBRUY7QUFGZCxDQUxnQixFQVNoQkwsWUFUZ0IsQ0FBbEIsQyIsImZpbGUiOiJwYXRyb25fZGF0YWJhc2VfbGlzdC5qcyIsInNvdXJjZXNDb250ZW50IjpbImltcG9ydCBhY2Nlc3NpYmxlQXV0b2NvbXBsZXRlIGZyb20gJ2FjY2Vzc2libGUtYXV0b2NvbXBsZXRlJztcblxuZXhwb3J0IGZ1bmN0aW9uIGNyZWF0ZUF1dG9jb21wbGV0ZShcbiAgICBzZWxlY3RvcixcbiAgICBpZCxcbiAgICBhcGlFbmRwb2ludCxcbiAgICBuYW1lLFxuICAgIHRlbXBsYXRlcyxcbiAgICBvbkNvbmZpcm1DYWxsYmFjayA9IChxLCBwKSA9PiB7fVxuKSB7XG4gICAgYWNjZXNzaWJsZUF1dG9jb21wbGV0ZSh7XG4gICAgICAgIGVsZW1lbnQ6IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3Ioc2VsZWN0b3IpLFxuICAgICAgICBpZDogaWQsXG4gICAgICAgIHNob3dOb09wdGlvbnNGb3VuZDogZmFsc2UsXG4gICAgICAgIHNvdXJjZTogZ2V0U3VnZ2VzdENhbGxiYWNrKGFwaUVuZHBvaW50KSxcbiAgICAgICAgbmFtZTogbmFtZSxcbiAgICAgICAgdGVtcGxhdGVzOiB0ZW1wbGF0ZXMsXG4gICAgICAgIG9uQ29uZmlybTogb25Db25maXJtQ2FsbGJhY2tcbiAgICAgIH0pXG59XG5cbmZ1bmN0aW9uIGdldFN1Z2dlc3RDYWxsYmFjayhhcGlFbmRwb2ludCkge1xuICAgIHJldHVybiBmdW5jdGlvbiBzdWdnZXN0IChxdWVyeSwgcG9wdWxhdGVSZXN1bHRzKSB7XG4gICAgICAgIGZldGNoKGFwaUVuZHBvaW50K3F1ZXJ5KVxuICAgICAgICAudGhlbihyZXNwb25zZSA9PiByZXNwb25zZS5qc29uKCkpXG4gICAgICAgIC50aGVuKGRhdGEgPT4gcG9wdWxhdGVSZXN1bHRzKGRhdGEpKTtcbiAgICAgIFxuICAgIH07XG59XG4iLCJpbXBvcnQge2NyZWF0ZUF1dG9jb21wbGV0ZX0gZnJvbSAnLi4vYXV0b2NvbXBsZXRlJztcblxuZnVuY3Rpb24gZ29Ub0RhdGFiYXNlKHJlc3VsdCkge1xuICB3aW5kb3cubG9jYXRpb24uaHJlZiA9IHJlc3VsdFsndXJsJ107XG59XG5cbmZ1bmN0aW9uIHRlbXBsYXRlKHJlc3VsdCkge1xuICByZXR1cm4gcmVzdWx0ICYmIHJlc3VsdFsndGl0bGUnXTtcbn1cblxuY3JlYXRlQXV0b2NvbXBsZXRlKFxuICAnI215LWF1dG9jb21wbGV0ZS1jb250YWluZXInLFxuICAnbXktYXV0b2NvbXBsZXRlJyxcbiAgJy9hcGkvYXV0b2NvbXBsZXRlL2RhdGFiYXNlcy5qc29uP3F1ZXJ5PScsXG4gICdzZWFyY2h0ZXJtJyxcbiAge1xuICAgIGlucHV0VmFsdWU6IHRlbXBsYXRlLFxuICAgIHN1Z2dlc3Rpb246IHRlbXBsYXRlLFxuICB9LFxuICBnb1RvRGF0YWJhc2Vcbik7XG4iXSwic291cmNlUm9vdCI6IiJ9