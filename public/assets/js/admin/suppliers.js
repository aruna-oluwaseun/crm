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
/******/ 	return __webpack_require__(__webpack_require__.s = 20);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/admin/suppliers.js":
/*!*****************************************!*\
  !*** ./resources/js/admin/suppliers.js ***!
  \*****************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(document).ready(function () {
  $('.copy-address-to-shipping').on('click', function (e) {
    e.preventDefault();
    var parent = $(this).parents('.address'); //#shipping-address-details
    //#billing-address-details

    $('[name="head_office_address_data[line1]"]').val(parent.find('[name="billing_address_data[line1]"]').val());
    $('[name="head_office_address_data[line2]"]').val(parent.find('[name="billing_address_data[line2]"]').val());
    $('[name="head_office_address_data[line3]"]').val(parent.find('[name="billing_address_data[line3]"]').val());
    $('[name="head_office_address_data[city]"]').val(parent.find('[name="billing_address_data[city]"]').val());
    $('[name="head_office_address_data[postcode]"]').val(parent.find('[name="billing_address_data[postcode]"]').val());
    $('[name="head_office_address_data[county]"]').val(parent.find('[name="billing_address_data[county]"]').val());
    $('[name="head_office_address_data[country]"]').val(parent.find('[name="billing_address_data[country]"]').val());
    $('[name="head_office_address_data[lat]"]').val(parent.find('[name="billing_address_data[lat]"]').val());
    $('[name="head_office_address_data[lng]"]').val(parent.find('[name="billing_address_data[lng]"]').val());
  });
  $('.copy-address-to-billing').on('click', function (e) {
    e.preventDefault();
    var parent = $(this).parents('.address'); //#shipping-address-details
    //#billing-address-details

    $('[name="billing_address_data[line1]"]').val(parent.find('[name="head_office_address_data[line1]"]').val());
    $('[name="billing_address_data[line2]"]').val(parent.find('[name="head_office_address_data[line2]"]').val());
    $('[name="billing_address_data[line3]"]').val(parent.find('[name="head_office_address_data[line3]"]').val());
    $('[name="billing_address_data[city]"]').val(parent.find('[name="head_office_address_data[city]"]').val());
    $('[name="billing_address_data[postcode]"]').val(parent.find('[name="head_office_address_data[postcode]"]').val());
    $('[name="billing_address_data[county]"]').val(parent.find('[name="head_office_address_data[county]"]').val());
    $('[name="billing_address_data[country]"]').val(parent.find('[name="head_office_address_data[country]"]').val());
    $('[name="billing_address_data[lat]"]').val(parent.find('[name="head_office_address_data[lat]"]').val());
    $('[name="billing_address_data[lng]"]').val(parent.find('[name="head_office_address_data[lng]"]').val());
  }); // change billing address

  $('#change-billing-address').click(function (e) {
    e.preventDefault();
    $('.new-billing-address').toggle();
  }); // change billing address

  $('#change-delivery-address').click(function (e) {
    e.preventDefault();
    $('.new-delivery-address').toggle();
  }); // show items on invo
});

/***/ }),

/***/ 20:
/*!***********************************************!*\
  !*** multi ./resources/js/admin/suppliers.js ***!
  \***********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Users/richardholmes/Sites/localhost/jenflow-laravel/resources/js/admin/suppliers.js */"./resources/js/admin/suppliers.js");


/***/ })

/******/ });