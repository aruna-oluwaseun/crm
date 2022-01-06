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
/******/ 	return __webpack_require__(__webpack_require__.s = 19);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/admin/invoice-detail.js":
/*!**********************************************!*\
  !*** ./resources/js/admin/invoice-detail.js ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(document).ready(function () {
  if (show_email_invoice_modal) {
    $('#modalInvoiceAction').modal('show');
  } // Email an invoice


  $('#modalEmailInvoice').on('shown.bs.modal', function (e) {
    var button = e.relatedTarget;
    var invoice_id = $(button).data('invoice-id');
    $('#modalEmailInvoice [name="invoice_id"]').val(invoice_id);
  }); // when invoicing allow immediate send of email

  $(document).on('click', '#email-invoice', function () {
    if ($(this).is(':checked')) {
      $('#email-for-invoice').show();
      $('#email-for-invoice input').removeAttr('disabled');
    } else {
      $('#email-for-invoice').hide();
      $('#email-for-invoice input').attr('disabled', 'disabled');
    }
  });
  $(document).on('click', '.checkbox-item-all, .checkbox-item', function () {
    if ($(this).hasClass('checkbox-item-all')) {
      if ($(this).is(':checked')) {
        // uncheck all
        $('#dispatch-items-form tbody input.checkbox-item').prop('checked', true);
      } else {
        // check all
        $('#dispatch-items-form tbody input.checkbox-item').prop('checked', false);
      }
    }

    var checked = $('#dispatch-items-form tbody input.checkbox-item:checked').length;

    if (checked) {
      $('#dispatch-btn').removeClass('disabled').removeAttr('disabled');
    } else {
      $('#dispatch-btn').addClass('disabled').attr('disabled', 'disabled');
    }
  }); // change tab

  $(document).on('click', '.tab-links li a', function (event) {
    event.preventDefault();
    $('.tab-links li a').removeClass('active');
    $('.tab').hide();
    $(this).addClass('active');
    $($(this).attr('href')).show();
    var href = window.location.href.replace(/#.*$/, '') + $(this).attr('href');
    window.history.pushState({
      href: href
    }, '', href);
  }); // Show tab on page load

  if ($(location).attr('hash').length) {
    $('.tab-links li a[href="' + $(location).attr('hash') + '"]').trigger('click');
  }
});

/***/ }),

/***/ 19:
/*!****************************************************!*\
  !*** multi ./resources/js/admin/invoice-detail.js ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Users/richardholmes/Sites/localhost/jenflow-laravel/resources/js/admin/invoice-detail.js */"./resources/js/admin/invoice-detail.js");


/***/ })

/******/ });