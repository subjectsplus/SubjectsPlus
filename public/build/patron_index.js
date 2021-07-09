(self["webpackChunksubjectsplus"] = self["webpackChunksubjectsplus"] || []).push([["patron_index"],{

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

/***/ "./assets/javascripts/patron/index.js":
/*!********************************************!*\
  !*** ./assets/javascripts/patron/index.js ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _autocomplete__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../autocomplete */ "./assets/javascripts/autocomplete.js");


function goToGuide(result) {
  window.location.href = "/subjects/" + result['shortform'];
}

function template(result) {
  return result && result['subject'];
}

(0,_autocomplete__WEBPACK_IMPORTED_MODULE_0__.createAutocomplete)('#my-autocomplete-container', 'my-autocomplete', '/api/autocomplete/guides.json?query=', 'searchterm', {
  inputValue: template,
  suggestion: template
}, goToGuide);

/***/ })

},
/******/ __webpack_require__ => { // webpackRuntimeModules
/******/ "use strict";
/******/ 
/******/ var __webpack_exec__ = (moduleId) => (__webpack_require__(__webpack_require__.s = moduleId))
/******/ __webpack_require__.O(0, ["vendors-node_modules_core-js_modules_es_object_to-string_js-node_modules_core-js_modules_es_p-2a1352","vendors-node_modules_accessible-autocomplete_dist_accessible-autocomplete_min_js"], () => (__webpack_exec__("./assets/javascripts/patron/index.js")));
/******/ var __webpack_exports__ = __webpack_require__.O();
/******/ }
]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly9zdWJqZWN0c3BsdXMvLi9hc3NldHMvamF2YXNjcmlwdHMvYXV0b2NvbXBsZXRlLmpzIiwid2VicGFjazovL3N1YmplY3RzcGx1cy8uL2Fzc2V0cy9qYXZhc2NyaXB0cy9wYXRyb24vaW5kZXguanMiXSwibmFtZXMiOlsiY3JlYXRlQXV0b2NvbXBsZXRlIiwic2VsZWN0b3IiLCJpZCIsImFwaUVuZHBvaW50IiwibmFtZSIsInRlbXBsYXRlcyIsIm9uQ29uZmlybUNhbGxiYWNrIiwicSIsInAiLCJhY2Nlc3NpYmxlQXV0b2NvbXBsZXRlIiwiZWxlbWVudCIsImRvY3VtZW50IiwicXVlcnlTZWxlY3RvciIsInNob3dOb09wdGlvbnNGb3VuZCIsInNvdXJjZSIsImdldFN1Z2dlc3RDYWxsYmFjayIsIm9uQ29uZmlybSIsInN1Z2dlc3QiLCJxdWVyeSIsInBvcHVsYXRlUmVzdWx0cyIsImZldGNoIiwidGhlbiIsInJlc3BvbnNlIiwianNvbiIsImRhdGEiLCJnb1RvR3VpZGUiLCJyZXN1bHQiLCJ3aW5kb3ciLCJsb2NhdGlvbiIsImhyZWYiLCJ0ZW1wbGF0ZSIsImlucHV0VmFsdWUiLCJzdWdnZXN0aW9uIl0sIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7QUFBQTtBQUVPLFNBQVNBLGtCQUFULENBQ0hDLFFBREcsRUFFSEMsRUFGRyxFQUdIQyxXQUhHLEVBSUhDLElBSkcsRUFLSEMsU0FMRyxFQU9MO0FBQUEsTUFERUMsaUJBQ0YsdUVBRHNCLFVBQUNDLENBQUQsRUFBSUMsQ0FBSixFQUFVLENBQUUsQ0FDbEM7QUFDRUMsZ0VBQXNCLENBQUM7QUFDbkJDLFdBQU8sRUFBRUMsUUFBUSxDQUFDQyxhQUFULENBQXVCWCxRQUF2QixDQURVO0FBRW5CQyxNQUFFLEVBQUVBLEVBRmU7QUFHbkJXLHNCQUFrQixFQUFFLEtBSEQ7QUFJbkJDLFVBQU0sRUFBRUMsa0JBQWtCLENBQUNaLFdBQUQsQ0FKUDtBQUtuQkMsUUFBSSxFQUFFQSxJQUxhO0FBTW5CQyxhQUFTLEVBQUVBLFNBTlE7QUFPbkJXLGFBQVMsRUFBRVY7QUFQUSxHQUFELENBQXRCO0FBU0g7O0FBRUQsU0FBU1Msa0JBQVQsQ0FBNEJaLFdBQTVCLEVBQXlDO0FBQ3JDLFNBQU8sU0FBU2MsT0FBVCxDQUFrQkMsS0FBbEIsRUFBeUJDLGVBQXpCLEVBQTBDO0FBQzdDQyxTQUFLLENBQUNqQixXQUFXLEdBQUNlLEtBQWIsQ0FBTCxDQUNDRyxJQURELENBQ00sVUFBQUMsUUFBUTtBQUFBLGFBQUlBLFFBQVEsQ0FBQ0MsSUFBVCxFQUFKO0FBQUEsS0FEZCxFQUVDRixJQUZELENBRU0sVUFBQUcsSUFBSTtBQUFBLGFBQUlMLGVBQWUsQ0FBQ0ssSUFBRCxDQUFuQjtBQUFBLEtBRlY7QUFJSCxHQUxEO0FBTUgsQzs7Ozs7Ozs7Ozs7OztBQzVCRDs7QUFFQSxTQUFTQyxTQUFULENBQW1CQyxNQUFuQixFQUEyQjtBQUN6QkMsUUFBTSxDQUFDQyxRQUFQLENBQWdCQyxJQUFoQixHQUF1QixlQUFlSCxNQUFNLENBQUMsV0FBRCxDQUE1QztBQUNEOztBQUVELFNBQVNJLFFBQVQsQ0FBa0JKLE1BQWxCLEVBQTBCO0FBQ3hCLFNBQU9BLE1BQU0sSUFBSUEsTUFBTSxDQUFDLFNBQUQsQ0FBdkI7QUFDRDs7QUFFRDFCLGlFQUFrQixDQUNoQiw0QkFEZ0IsRUFFaEIsaUJBRmdCLEVBR2hCLHNDQUhnQixFQUloQixZQUpnQixFQUtoQjtBQUNFK0IsWUFBVSxFQUFFRCxRQURkO0FBRUVFLFlBQVUsRUFBRUY7QUFGZCxDQUxnQixFQVNoQkwsU0FUZ0IsQ0FBbEIsQyIsImZpbGUiOiJwYXRyb25faW5kZXguanMiLCJzb3VyY2VzQ29udGVudCI6WyJpbXBvcnQgYWNjZXNzaWJsZUF1dG9jb21wbGV0ZSBmcm9tICdhY2Nlc3NpYmxlLWF1dG9jb21wbGV0ZSc7XG5cbmV4cG9ydCBmdW5jdGlvbiBjcmVhdGVBdXRvY29tcGxldGUoXG4gICAgc2VsZWN0b3IsXG4gICAgaWQsXG4gICAgYXBpRW5kcG9pbnQsXG4gICAgbmFtZSxcbiAgICB0ZW1wbGF0ZXMsXG4gICAgb25Db25maXJtQ2FsbGJhY2sgPSAocSwgcCkgPT4ge31cbikge1xuICAgIGFjY2Vzc2libGVBdXRvY29tcGxldGUoe1xuICAgICAgICBlbGVtZW50OiBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKHNlbGVjdG9yKSxcbiAgICAgICAgaWQ6IGlkLFxuICAgICAgICBzaG93Tm9PcHRpb25zRm91bmQ6IGZhbHNlLFxuICAgICAgICBzb3VyY2U6IGdldFN1Z2dlc3RDYWxsYmFjayhhcGlFbmRwb2ludCksXG4gICAgICAgIG5hbWU6IG5hbWUsXG4gICAgICAgIHRlbXBsYXRlczogdGVtcGxhdGVzLFxuICAgICAgICBvbkNvbmZpcm06IG9uQ29uZmlybUNhbGxiYWNrXG4gICAgICB9KVxufVxuXG5mdW5jdGlvbiBnZXRTdWdnZXN0Q2FsbGJhY2soYXBpRW5kcG9pbnQpIHtcbiAgICByZXR1cm4gZnVuY3Rpb24gc3VnZ2VzdCAocXVlcnksIHBvcHVsYXRlUmVzdWx0cykge1xuICAgICAgICBmZXRjaChhcGlFbmRwb2ludCtxdWVyeSlcbiAgICAgICAgLnRoZW4ocmVzcG9uc2UgPT4gcmVzcG9uc2UuanNvbigpKVxuICAgICAgICAudGhlbihkYXRhID0+IHBvcHVsYXRlUmVzdWx0cyhkYXRhKSk7XG4gICAgICBcbiAgICB9O1xufVxuIiwiaW1wb3J0IHtjcmVhdGVBdXRvY29tcGxldGV9IGZyb20gJy4uL2F1dG9jb21wbGV0ZSc7XG5cbmZ1bmN0aW9uIGdvVG9HdWlkZShyZXN1bHQpIHtcbiAgd2luZG93LmxvY2F0aW9uLmhyZWYgPSBcIi9zdWJqZWN0cy9cIiArIHJlc3VsdFsnc2hvcnRmb3JtJ107XG59XG5cbmZ1bmN0aW9uIHRlbXBsYXRlKHJlc3VsdCkge1xuICByZXR1cm4gcmVzdWx0ICYmIHJlc3VsdFsnc3ViamVjdCddO1xufVxuXG5jcmVhdGVBdXRvY29tcGxldGUoXG4gICcjbXktYXV0b2NvbXBsZXRlLWNvbnRhaW5lcicsXG4gICdteS1hdXRvY29tcGxldGUnLFxuICAnL2FwaS9hdXRvY29tcGxldGUvZ3VpZGVzLmpzb24/cXVlcnk9JyxcbiAgJ3NlYXJjaHRlcm0nLFxuICB7XG4gICAgaW5wdXRWYWx1ZTogdGVtcGxhdGUsXG4gICAgc3VnZ2VzdGlvbjogdGVtcGxhdGUsXG4gIH0sXG4gIGdvVG9HdWlkZVxuKTtcbiJdLCJzb3VyY2VSb290IjoiIn0=