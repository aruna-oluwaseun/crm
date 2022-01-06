/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 15);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/admin/product-list.js":
/*!********************************************!*\
  !*** ./resources/js/admin/product-list.js ***!
  \********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(document).ready(function () {
  /**
   * Manage product stats
   */
  $(document).on('click', '.btn-action', function (event) {
    event.preventDefault();
    var button = $(this);
    var data_box = $(this).parents('.data-container');
    var action = $(this).data('action');
    var url = $(this).attr('href');
    var id = data_box.data('id');
    var active = '<a href="/product-status" class="btn btn-trigger btn-action btn-icon" data-action="active" data-toggle="tooltip" data-placement="top" title="Enable product"><em class="icon ni ni-check-thick"></em></a>';
    var disable = '<a href="/product-status" class="btn btn-trigger btn-action btn-icon" data-action="disabled" data-toggle="tooltip" data-placement="top" title="Disable product"><em class="icon ni ni-cross"></em></a>';
    $.ajax({
      method: 'GET',
      url: url,
      data: 'status=' + action + '&id=' + id + '&_token=' + $('meta[name="csrf-token"]').attr('content'),
      async: false,
      success: function success(data, textStatus, XHR) {
        if (data.success) {
          if (action == 'active') {
            $('[data-id="' + id + '"]').find('.tb-status').removeClass('text-warning').addClass('text-success').text('Active');
            data_box.find('.status-result').html(disable);
          } else {
            $('[data-id="' + id + '"]').find('.tb-status').removeClass('text-success').addClass('text-warning').text('Disabled');
            data_box.find('.status-result').html(active);
          }

          Swal.fire({
            icon: 'success',
            title: 'Success',
            text: 'Product status updated.' //footer: '<a href>Why do I have this issue?</a>'

          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: data.message ? data.message : 'Something went wrong! please re-fresh your page and try again.' //footer: '<a href>Why do I have this issue?</a>'

          });
        }
      },
      error: function error(XHR, textStatus, _error) {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Your session may have expired, please re-fresh your page and try again.' //footer: '<a href>Why do I have this issue?</a>'

        });
      }
    });
  });
});

/***/ }),

/***/ 15:
/*!**************************************************!*\
  !*** multi ./resources/js/admin/product-list.js ***!
  \**************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Users/richardholmes/Sites/localhost/jenflow-laravel/resources/js/admin/product-list.js */"./resources/js/admin/product-list.js");


/***/ })

/******/ });