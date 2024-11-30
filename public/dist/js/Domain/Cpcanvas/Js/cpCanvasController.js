"use strict";
(function webpackUniversalModuleDefinition(root, factory) {
	if(typeof exports === 'object' && typeof module === 'object')
		module.exports = factory(require("window.leantime.i18n"));
	else if(typeof define === 'function' && define.amd)
		define(["window.leantime.i18n"], factory);
	else {
		var a = typeof exports === 'object' ? factory(require("window.leantime.i18n")) : factory(root["window.leantime.i18n"]);
		for(var i in a) (typeof exports === 'object' ? exports : root)[i] = a[i];
	}
})(self, (__WEBPACK_EXTERNAL_MODULE_i18n__) => {
return (self["webpackChunkleantime"] = self["webpackChunkleantime"] || []).push([["/js/Domain/Cpcanvas/Js/cpCanvasController"],{

/***/ "./app/Domain/Cpcanvas/Js/cpCanvasController.js":
/*!******************************************************!*\
  !*** ./app/Domain/Cpcanvas/Js/cpCanvasController.js ***!
  \******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__),
/* harmony export */   setRowHeights: () => (/* binding */ setRowHeights)
/* harmony export */ });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! i18n */ "i18n");
/* harmony import */ var i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var js_app_core_instance_info_module__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! js/app/core/instance-info.module */ "./public/assets/js/app/core/instance-info.module.mjs");




// To be set
var canvasName = 'cp';

// To be implemented
var setRowHeights = function setRowHeights() {
  var nbRows = 4;
  var rowHeight = jquery__WEBPACK_IMPORTED_MODULE_0___default()("html").height() - 320 - 20 * nbRows - 5 * 25;
  var firstRowHeight = rowHeight / nbRows;
  jquery__WEBPACK_IMPORTED_MODULE_0___default()("#firstRow div.contentInner").each(function () {
    if (jquery__WEBPACK_IMPORTED_MODULE_0___default()(this).height() > firstRowHeight) {
      firstRowHeight = jquery__WEBPACK_IMPORTED_MODULE_0___default()(this).height() + 50;
    }
  });
  jquery__WEBPACK_IMPORTED_MODULE_0___default()("#firstRow .column .contentInner").css("height", firstRowHeight);
  var secondRowHeight = rowHeight / nbRows;
  jquery__WEBPACK_IMPORTED_MODULE_0___default()("#secondRow div.contentInner").each(function () {
    if (jquery__WEBPACK_IMPORTED_MODULE_0___default()(this).height() > secondRowHeight) {
      secondRowHeight = jquery__WEBPACK_IMPORTED_MODULE_0___default()(this).height() + 50;
    }
  });
  jquery__WEBPACK_IMPORTED_MODULE_0___default()("#secondRow .column .contentInner").css("height", secondRowHeight);
  var thirdRowHeight = rowHeight / nbRows;
  jquery__WEBPACK_IMPORTED_MODULE_0___default()("#thirdRow div.contentInner").each(function () {
    if (jquery__WEBPACK_IMPORTED_MODULE_0___default()(this).height() > thirdRowHeight) {
      thirdRowHeight = jquery__WEBPACK_IMPORTED_MODULE_0___default()(this).height() + 50;
    }
  });
  jquery__WEBPACK_IMPORTED_MODULE_0___default()("#thirdRow .column .contentInner").css("height", thirdRowHeight);
  var fourthRowHeight = rowHeight / nbRows;
  jquery__WEBPACK_IMPORTED_MODULE_0___default()("#fourthRow div.contentInner").each(function () {
    if (jquery__WEBPACK_IMPORTED_MODULE_0___default()(this).height() > fourthRowHeight) {
      fourthRowHeight = jquery__WEBPACK_IMPORTED_MODULE_0___default()(this).height() + 50;
    }
  });
  jquery__WEBPACK_IMPORTED_MODULE_0___default()("#fourthRow .column .contentInner").css("height", fourthRowHeight);
};

// --- Internal (not to be changed beyond this point) ---

var closeModal = false;
var initUserDropdown = function initUserDropdown() {
  jquery__WEBPACK_IMPORTED_MODULE_0___default()("body").on("click", ".userDropdown .dropdown-menu a", function () {
    var dataValue = jquery__WEBPACK_IMPORTED_MODULE_0___default()(this).attr("data-value").split("_");
    var dataLabel = jquery__WEBPACK_IMPORTED_MODULE_0___default()(this).attr('data-label');
    if (dataValue.length == 3) {
      var canvasId = dataValue[0];
      var userId = dataValue[1];
      var profileImageId = dataValue[2];
      jquery__WEBPACK_IMPORTED_MODULE_0___default().ajax({
        type: 'PATCH',
        url: js_app_core_instance_info_module__WEBPACK_IMPORTED_MODULE_2__.appUrl + '/api/' + canvasName + 'canvas',
        data: {
          id: canvasId,
          author: userId
        }
      }).done(function () {
        jquery__WEBPACK_IMPORTED_MODULE_0___default()("#userDropdownMenuLink" + canvasId + " span.text span#userImage" + canvasId + " img").attr("src", js_app_core_instance_info_module__WEBPACK_IMPORTED_MODULE_2__.appUrl + "/api/users?profileImage=" + userId);
        jquery__WEBPACK_IMPORTED_MODULE_0___default().growl({
          message: i18n__WEBPACK_IMPORTED_MODULE_1___default().__("short_notifications.user_updated"),
          style: "success"
        });
      });
    }
  });
};
var initStatusDropdown = function initStatusDropdown() {
  jquery__WEBPACK_IMPORTED_MODULE_0___default()("body").on("click", ".statusDropdown .dropdown-menu a", function () {
    var dataValue = jquery__WEBPACK_IMPORTED_MODULE_0___default()(this).attr("data-value").split("/");
    var dataLabel = jquery__WEBPACK_IMPORTED_MODULE_0___default()(this).attr('data-label');
    if (dataValue.length == 2) {
      var canvasItemId = dataValue[0];
      var status = dataValue[1];
      var statusClass = jquery__WEBPACK_IMPORTED_MODULE_0___default()(this).attr('class');
      jquery__WEBPACK_IMPORTED_MODULE_0___default().ajax({
        type: 'PATCH',
        url: js_app_core_instance_info_module__WEBPACK_IMPORTED_MODULE_2__.appUrl + '/api/' + canvasName + 'canvas',
        data: {
          id: canvasItemId,
          status: status
        }
      }).done(function () {
        jquery__WEBPACK_IMPORTED_MODULE_0___default()("#statusDropdownMenuLink" + canvasItemId + " span.text").text(dataLabel);
        jquery__WEBPACK_IMPORTED_MODULE_0___default()("#statusDropdownMenuLink" + canvasItemId).removeClass().addClass(statusClass + " dropdown-toggle f-left status ");
        jquery__WEBPACK_IMPORTED_MODULE_0___default().growl({
          message: i18n__WEBPACK_IMPORTED_MODULE_1___default().__("short_notifications.status_updated")
        });
      });
    }
  });
};
var initRelatesDropdown = function initRelatesDropdown() {
  jquery__WEBPACK_IMPORTED_MODULE_0___default()("body").on("click", ".relatesDropdown .dropdown-menu a", function () {
    var dataValue = jquery__WEBPACK_IMPORTED_MODULE_0___default()(this).attr("data-value").split("/");
    var dataLabel = jquery__WEBPACK_IMPORTED_MODULE_0___default()(this).attr('data-label');
    if (dataValue.length == 2) {
      var canvasItemId = dataValue[0];
      var relates = dataValue[1];
      var relatesClass = jquery__WEBPACK_IMPORTED_MODULE_0___default()(this).attr('class');
      jquery__WEBPACK_IMPORTED_MODULE_0___default().ajax({
        type: 'PATCH',
        url: js_app_core_instance_info_module__WEBPACK_IMPORTED_MODULE_2__.appUrl + '/api/' + canvasName + 'canvas',
        data: {
          id: canvasItemId,
          relates: relates
        }
      }).done(function () {
        jquery__WEBPACK_IMPORTED_MODULE_0___default()("#relatesDropdownMenuLink" + canvasItemId + " span.text").text(dataLabel);
        jquery__WEBPACK_IMPORTED_MODULE_0___default()("#relatesDropdownMenuLink" + canvasItemId).removeClass().addClass(relatesClass + " dropdown-toggle f-left relates ");
        jquery__WEBPACK_IMPORTED_MODULE_0___default().growl({
          message: i18n__WEBPACK_IMPORTED_MODULE_1___default().__("short_notifications.relates_updated")
        });
      });
    }
  });
};

// Make public what you want to have public, everything else is private
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  initUserDropdown: initUserDropdown,
  initStatusDropdown: initStatusDropdown,
  initRelatesDropdown: initRelatesDropdown,
  setRowHeights: setRowHeights
});

/***/ }),

/***/ "i18n":
/*!***************************************!*\
  !*** external "window.leantime.i18n" ***!
  \***************************************/
/***/ ((module) => {

module.exports = __WEBPACK_EXTERNAL_MODULE_i18n__;

/***/ }),

/***/ "./public/assets/js/app/core/instance-info.module.mjs":
/*!************************************************************!*\
  !*** ./public/assets/js/app/core/instance-info.module.mjs ***!
  \************************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   appUrl: () => (/* binding */ appUrl),
/* harmony export */   colorScheme: () => (/* binding */ colorScheme),
/* harmony export */   companyColor: () => (/* binding */ companyColor),
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__),
/* harmony export */   theme: () => (/* binding */ theme),
/* harmony export */   version: () => (/* binding */ version)
/* harmony export */ });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js");

var companyColor = jquery__WEBPACK_IMPORTED_MODULE_0__('meta[name=theme-color]').attr('content');
var colorScheme = jquery__WEBPACK_IMPORTED_MODULE_0__('meta[name=color-scheme]').attr('content');
var theme = jquery__WEBPACK_IMPORTED_MODULE_0__('meta[name=theme]').attr('content');
var appUrl = jquery__WEBPACK_IMPORTED_MODULE_0__('meta[name=identifier-URL]').attr('content');
var version = jquery__WEBPACK_IMPORTED_MODULE_0__('meta[name=leantime-version]').attr('content');
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  companyColor: companyColor,
  colorScheme: colorScheme,
  theme: theme,
  appUrl: appUrl,
  version: version
});

/***/ })

},
/******/ __webpack_require__ => { // webpackRuntimeModules
/******/ var __webpack_exec__ = (moduleId) => (__webpack_require__(__webpack_require__.s = moduleId))
/******/ __webpack_require__.O(0, ["js/vendor"], () => (__webpack_exec__("./app/Domain/Cpcanvas/Js/cpCanvasController.js")));
/******/ var __webpack_exports__ = __webpack_require__.O();
/******/ return __webpack_exports__;
/******/ }
]);
});