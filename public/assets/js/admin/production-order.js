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
/******/ 	return __webpack_require__(__webpack_require__.s = 12);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/admin/production-order.js":
/*!************************************************!*\
  !*** ./resources/js/admin/production-order.js ***!
  \************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(document).ready(function () {
  $(document).on('change', '[name="status"]', function (event) {
    if ($(this).val() == 'completed') {
      $('#status-warning').show();
    } else {
      $('#status-warning').hide();
    }
  }); // Populate form data

  $(document).on('change', '#add-product-id', function (event) {
    $('#fetch-product-status').fadeOut(400);
    var selected = $(this).find(':selected').val(); // fetch product

    $.ajax({
      method: 'GET',
      url: '/get-product',
      data: 'id=' + selected,
      success: function success(response) {
        if (response.success) {
          if (response.data.weight && response.data.unit_of_measure_id) {
            $('#add-qty').val(response.data.weight);
          }

          if (response.data.unit_of_measure_id) {
            $('#add-unit-of-measure-id').val(response.data.unit_of_measure_id).trigger('change');
          } else {
            $('#add-unit-of-measure-id').val('unit').trigger('change');
          }
        } else {
          $('#fetch-product-status').html('Error fetching the selected product to auto populate the form but the request failed, you can manually fill the form in.').show();
        }
      },
      error: function error() {
        $('#fetch-product-status').html('Error fetching the selected product to auto populate the form but the request failed, your session may have expired, please re-fresh and try again.').show();
      }
    });
  }); // Add build product

  $(document).on('submit', '#create-form', function (event) {
    event.preventDefault();
    var url = $(this).attr('action');
    var method = $(this).attr('method');
    var button = $(this).find('.submit-btn');
    button.attr('disabled', 'disabled').text('...Please wait');
    $.ajax({
      method: method,
      url: url,
      data: $(this).serialize(),
      async: false,
      success: function success(response, textStatus, XHR) {
        if (response.success) {
          // add to table
          if ($('#build-products').length) {
            var clone = $('#build-product-prototype tbody').html();
            /** @TODO Sort qty display out */

            clone = clone.replace(/{ID}/g, response.data.id);
            clone = clone.replace(/{PRODUCT_TITLE}/g, response.data.product_title);
            clone = clone.replace(/{QTY}/g, response.data.qty);
            clone = clone.replace(/{PRODUCT_ID}/g, response.data.product_id);
            $('#build-products tbody').prepend(clone);
            $('#create-form').trigger('reset');
            $('#modalCreate').modal('hide');
            Swal.fire({
              icon: 'success',
              title: 'Success',
              text: response.message ? response.message : 'Build product added.' //footer: '<a href>Why do I have this issue?</a>'

            });
          } else {
            Swal.fire({
              icon: 'success',
              title: 'Success',
              text: response.message ? response.message : 'Build product added.' //footer: '<a href>Why do I have this issue?</a>'

            });
            setTimeout(function () {
              location.reload();
            }, 900);
          }

          button.removeAttr('disabled').text('Save');
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: response.message ? response.message : 'Something went wrong! please re-fresh your page and try again.' //footer: '<a href>Why do I have this issue?</a>'

          });
          button.removeAttr('disabled').text('Save');
        }
      },
      error: function error(XHR, textStatus, _error) {
        if (XHR.status === 422) {
          var response = XHR.responseJSON;
          var errors = response.errors;
          var message = 'You have errors in your form.';
          $.each(errors, function (key, value) {
            $('#modalCreate [name="' + key + '"]').parent().append('<span id="' + key + '-error" class="invalid">This field is required.</span>');
            message += value;
          });
        }

        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: message ? message : 'Your session may have expired, please re-fresh your page and try again.' //footer: '<a href>Why do I have this issue?</a>'

        });
        button.removeAttr('disabled').text('Save');
      }
    });
  });
});

/***/ }),

/***/ 12:
/*!******************************************************!*\
  !*** multi ./resources/js/admin/production-order.js ***!
  \******************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Users/richardholmes/Sites/localhost/jenflow-laravel/resources/js/admin/production-order.js */"./resources/js/admin/production-order.js");


/***/ })

/******/ });