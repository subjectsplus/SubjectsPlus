(self["webpackChunksubjectsplus"] = self["webpackChunksubjectsplus"] || []).push([["linkcheck"],{

/***/ "./assets/javascripts/staff/linkcheck.js":
/*!***********************************************!*\
  !*** ./assets/javascripts/staff/linkcheck.js ***!
  \***********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/es.object.to-string.js */ "./node_modules/core-js/modules/es.object.to-string.js");
/* harmony import */ var core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var core_js_modules_es_promise_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! core-js/modules/es.promise.js */ "./node_modules/core-js/modules/es.promise.js");
/* harmony import */ var core_js_modules_es_promise_js__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_promise_js__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var core_js_modules_es_array_slice_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! core-js/modules/es.array.slice.js */ "./node_modules/core-js/modules/es.array.slice.js");
/* harmony import */ var core_js_modules_es_array_slice_js__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_array_slice_js__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var core_js_modules_es_function_name_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! core-js/modules/es.function.name.js */ "./node_modules/core-js/modules/es.function.name.js");
/* harmony import */ var core_js_modules_es_function_name_js__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_function_name_js__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var core_js_modules_es_array_from_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! core-js/modules/es.array.from.js */ "./node_modules/core-js/modules/es.array.from.js");
/* harmony import */ var core_js_modules_es_array_from_js__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_array_from_js__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var core_js_modules_es_string_iterator_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! core-js/modules/es.string.iterator.js */ "./node_modules/core-js/modules/es.string.iterator.js");
/* harmony import */ var core_js_modules_es_string_iterator_js__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_string_iterator_js__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var core_js_modules_es_symbol_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! core-js/modules/es.symbol.js */ "./node_modules/core-js/modules/es.symbol.js");
/* harmony import */ var core_js_modules_es_symbol_js__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_symbol_js__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var core_js_modules_es_symbol_description_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! core-js/modules/es.symbol.description.js */ "./node_modules/core-js/modules/es.symbol.description.js");
/* harmony import */ var core_js_modules_es_symbol_description_js__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_symbol_description_js__WEBPACK_IMPORTED_MODULE_7__);
/* harmony import */ var core_js_modules_es_symbol_iterator_js__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! core-js/modules/es.symbol.iterator.js */ "./node_modules/core-js/modules/es.symbol.iterator.js");
/* harmony import */ var core_js_modules_es_symbol_iterator_js__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_symbol_iterator_js__WEBPACK_IMPORTED_MODULE_8__);
/* harmony import */ var core_js_modules_es_array_iterator_js__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! core-js/modules/es.array.iterator.js */ "./node_modules/core-js/modules/es.array.iterator.js");
/* harmony import */ var core_js_modules_es_array_iterator_js__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_array_iterator_js__WEBPACK_IMPORTED_MODULE_9__);
/* harmony import */ var core_js_modules_web_dom_collections_iterator_js__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! core-js/modules/web.dom-collections.iterator.js */ "./node_modules/core-js/modules/web.dom-collections.iterator.js");
/* harmony import */ var core_js_modules_web_dom_collections_iterator_js__WEBPACK_IMPORTED_MODULE_10___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_web_dom_collections_iterator_js__WEBPACK_IMPORTED_MODULE_10__);
/* harmony import */ var core_js_modules_es_array_is_array_js__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! core-js/modules/es.array.is-array.js */ "./node_modules/core-js/modules/es.array.is-array.js");
/* harmony import */ var core_js_modules_es_array_is_array_js__WEBPACK_IMPORTED_MODULE_11___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_array_is_array_js__WEBPACK_IMPORTED_MODULE_11__);
/* harmony import */ var bootstrap__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! bootstrap */ "./node_modules/bootstrap/dist/js/bootstrap.esm.js");
function _createForOfIteratorHelper(o, allowArrayLike) { var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"]; if (!it) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = it.call(o); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it["return"] != null) it["return"](); } finally { if (didErr) throw err; } } }; }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }














var checkLinksButton = document.getElementById('check-links-button');
var resultsTable = document.getElementById('results-table');
var paramsCollapse = new bootstrap__WEBPACK_IMPORTED_MODULE_12__.Collapse(document.getElementById('collapseParams', {
  toggle: false
}));
var resultsCollapse = new bootstrap__WEBPACK_IMPORTED_MODULE_12__.Collapse(document.getElementById('collapseResults', {
  toggle: false
}));
paramsCollapse.show();
resultsCollapse.hide();
checkLinksButton.addEventListener('click', function () {
  paramsCollapse.hide();
  fetch('/control/guides/check_links.json').then(function (response) {
    return response.json();
  }).then(function (data) {
    var _iterator = _createForOfIteratorHelper(data),
        _step;

    try {
      for (_iterator.s(); !(_step = _iterator.n()).done;) {
        var result = _step.value;
        var row = resultsTable.insertRow();
        var linkCell = row.insertCell();
        linkCell.innerHTML = result['url'];
        var statusCell = row.insertCell();
        statusCell.innerHTML = result['status'];
        var boxCell = row.insertCell();
        boxCell.innerHTML = "Box";
      }
    } catch (err) {
      _iterator.e(err);
    } finally {
      _iterator.f();
    }

    resultsCollapse.show();
  });
});

/***/ })

},
/******/ __webpack_require__ => { // webpackRuntimeModules
/******/ "use strict";
/******/ 
/******/ var __webpack_exec__ = (moduleId) => (__webpack_require__(__webpack_require__.s = moduleId))
/******/ __webpack_require__.O(0, ["vendors-node_modules_bootstrap_dist_js_bootstrap_esm_js","vendors-node_modules_core-js_modules_es_object_to-string_js-node_modules_core-js_modules_es_p-2a1352","vendors-node_modules_core-js_modules_es_array_from_js-node_modules_core-js_modules_es_array_i-3637c8"], () => (__webpack_exec__("./assets/javascripts/staff/linkcheck.js")));
/******/ var __webpack_exports__ = __webpack_require__.O();
/******/ }
]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly9zdWJqZWN0c3BsdXMvLi9hc3NldHMvamF2YXNjcmlwdHMvc3RhZmYvbGlua2NoZWNrLmpzIl0sIm5hbWVzIjpbImNoZWNrTGlua3NCdXR0b24iLCJkb2N1bWVudCIsImdldEVsZW1lbnRCeUlkIiwicmVzdWx0c1RhYmxlIiwicGFyYW1zQ29sbGFwc2UiLCJDb2xsYXBzZSIsInRvZ2dsZSIsInJlc3VsdHNDb2xsYXBzZSIsInNob3ciLCJoaWRlIiwiYWRkRXZlbnRMaXN0ZW5lciIsImZldGNoIiwidGhlbiIsInJlc3BvbnNlIiwianNvbiIsImRhdGEiLCJyZXN1bHQiLCJyb3ciLCJpbnNlcnRSb3ciLCJsaW5rQ2VsbCIsImluc2VydENlbGwiLCJpbm5lckhUTUwiLCJzdGF0dXNDZWxsIiwiYm94Q2VsbCJdLCJtYXBwaW5ncyI6Ijs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7QUFBQTtBQUVBLElBQU1BLGdCQUFnQixHQUFHQyxRQUFRLENBQUNDLGNBQVQsQ0FBd0Isb0JBQXhCLENBQXpCO0FBQ0EsSUFBTUMsWUFBWSxHQUFHRixRQUFRLENBQUNDLGNBQVQsQ0FBd0IsZUFBeEIsQ0FBckI7QUFFQSxJQUFNRSxjQUFjLEdBQUcsSUFBSUMsZ0RBQUosQ0FBYUosUUFBUSxDQUFDQyxjQUFULENBQXdCLGdCQUF4QixFQUEwQztBQUFDSSxRQUFNLEVBQUU7QUFBVCxDQUExQyxDQUFiLENBQXZCO0FBQ0EsSUFBTUMsZUFBZSxHQUFHLElBQUlGLGdEQUFKLENBQWFKLFFBQVEsQ0FBQ0MsY0FBVCxDQUF3QixpQkFBeEIsRUFBMkM7QUFBQ0ksUUFBTSxFQUFFO0FBQVQsQ0FBM0MsQ0FBYixDQUF4QjtBQUVBRixjQUFjLENBQUNJLElBQWY7QUFDQUQsZUFBZSxDQUFDRSxJQUFoQjtBQUVBVCxnQkFBZ0IsQ0FBQ1UsZ0JBQWpCLENBQWtDLE9BQWxDLEVBQTJDLFlBQU07QUFDN0NOLGdCQUFjLENBQUNLLElBQWY7QUFDQUUsT0FBSyxDQUFDLGtDQUFELENBQUwsQ0FDQ0MsSUFERCxDQUNNLFVBQUFDLFFBQVE7QUFBQSxXQUFJQSxRQUFRLENBQUNDLElBQVQsRUFBSjtBQUFBLEdBRGQsRUFFQ0YsSUFGRCxDQUVNLFVBQUFHLElBQUksRUFBSTtBQUFBLCtDQUNTQSxJQURUO0FBQUE7O0FBQUE7QUFDViwwREFBeUI7QUFBQSxZQUFoQkMsTUFBZ0I7QUFDckIsWUFBSUMsR0FBRyxHQUFHZCxZQUFZLENBQUNlLFNBQWIsRUFBVjtBQUNBLFlBQUlDLFFBQVEsR0FBR0YsR0FBRyxDQUFDRyxVQUFKLEVBQWY7QUFDQUQsZ0JBQVEsQ0FBQ0UsU0FBVCxHQUFxQkwsTUFBTSxDQUFDLEtBQUQsQ0FBM0I7QUFFQSxZQUFJTSxVQUFVLEdBQUdMLEdBQUcsQ0FBQ0csVUFBSixFQUFqQjtBQUNBRSxrQkFBVSxDQUFDRCxTQUFYLEdBQXVCTCxNQUFNLENBQUMsUUFBRCxDQUE3QjtBQUVBLFlBQUlPLE9BQU8sR0FBR04sR0FBRyxDQUFDRyxVQUFKLEVBQWQ7QUFDQUcsZUFBTyxDQUFDRixTQUFSLEdBQW9CLEtBQXBCO0FBRUg7QUFaUztBQUFBO0FBQUE7QUFBQTtBQUFBOztBQWFWZCxtQkFBZSxDQUFDQyxJQUFoQjtBQUNILEdBaEJEO0FBaUJILENBbkJELEUiLCJmaWxlIjoibGlua2NoZWNrLmpzIiwic291cmNlc0NvbnRlbnQiOlsiaW1wb3J0IHsgQ29sbGFwc2UgfSBmcm9tICdib290c3RyYXAnO1xuXG5jb25zdCBjaGVja0xpbmtzQnV0dG9uID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2NoZWNrLWxpbmtzLWJ1dHRvbicpO1xuY29uc3QgcmVzdWx0c1RhYmxlID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3Jlc3VsdHMtdGFibGUnKTtcblxuY29uc3QgcGFyYW1zQ29sbGFwc2UgPSBuZXcgQ29sbGFwc2UoZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2NvbGxhcHNlUGFyYW1zJywge3RvZ2dsZTogZmFsc2V9KSk7XG5jb25zdCByZXN1bHRzQ29sbGFwc2UgPSBuZXcgQ29sbGFwc2UoZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2NvbGxhcHNlUmVzdWx0cycsIHt0b2dnbGU6IGZhbHNlfSkpO1xuXG5wYXJhbXNDb2xsYXBzZS5zaG93KCk7XG5yZXN1bHRzQ29sbGFwc2UuaGlkZSgpO1xuXG5jaGVja0xpbmtzQnV0dG9uLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgKCkgPT4ge1xuICAgIHBhcmFtc0NvbGxhcHNlLmhpZGUoKTtcbiAgICBmZXRjaCgnL2NvbnRyb2wvZ3VpZGVzL2NoZWNrX2xpbmtzLmpzb24nKVxuICAgIC50aGVuKHJlc3BvbnNlID0+IHJlc3BvbnNlLmpzb24oKSlcbiAgICAudGhlbihkYXRhID0+IHtcbiAgICAgICAgZm9yICh2YXIgcmVzdWx0IG9mIGRhdGEpIHtcbiAgICAgICAgICAgIHZhciByb3cgPSByZXN1bHRzVGFibGUuaW5zZXJ0Um93KCk7XG4gICAgICAgICAgICB2YXIgbGlua0NlbGwgPSByb3cuaW5zZXJ0Q2VsbCgpO1xuICAgICAgICAgICAgbGlua0NlbGwuaW5uZXJIVE1MID0gcmVzdWx0Wyd1cmwnXTtcblxuICAgICAgICAgICAgdmFyIHN0YXR1c0NlbGwgPSByb3cuaW5zZXJ0Q2VsbCgpO1xuICAgICAgICAgICAgc3RhdHVzQ2VsbC5pbm5lckhUTUwgPSByZXN1bHRbJ3N0YXR1cyddO1xuXG4gICAgICAgICAgICB2YXIgYm94Q2VsbCA9IHJvdy5pbnNlcnRDZWxsKCk7XG4gICAgICAgICAgICBib3hDZWxsLmlubmVySFRNTCA9IFwiQm94XCI7XG5cbiAgICAgICAgfVxuICAgICAgICByZXN1bHRzQ29sbGFwc2Uuc2hvdygpO1xuICAgIH0pXG59KSJdLCJzb3VyY2VSb290IjoiIn0=