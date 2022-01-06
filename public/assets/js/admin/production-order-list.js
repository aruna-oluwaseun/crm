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
/******/ 	return __webpack_require__(__webpack_require__.s = 13);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/admin/production-order-list.js":
/*!*****************************************************!*\
  !*** ./resources/js/admin/production-order-list.js ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(document).ready(function () {
  var table = false;

  if ($('.datatable-custom tbody tr').length) {
    table = $('.datatable-custom').DataTable(dataTable_options);
  }
  /**
   * New production order
   */


  $(document).on('submit', '#create-form', function (event) {
    event.preventDefault();
    var url = $(this).attr('action');
    var button = $(this).find('.submit-btn');
    button.attr('disabled', 'disabled').text('...Please wait');
    $.ajax({
      method: 'POST',
      url: url,
      data: $(this).serialize(),
      async: false,
      success: function success(response, textStatus, XHR) {
        if (response.success) {
          var id = response.id;

          if (table) {
            var row = table.row.add(['<td><a class="id" href="/productionorders/' + id + '">' + id + '</a></td>', '<td><div class="user-info"><span class="tb-lead title">' + $('[name="product_id"]').find(':selected').text() + '</span></div></td>', '<td><span class="tb-lead">' + $('[name="qty"]').val() + '</span></td>', '<td><span>' + $('[name="due_date"]').val() + '</span></td>', '<td><span class="tb-status status text-info">Pending</span></td>', '<td></td>']).draw().node(); // style rows

            $(row).find('tr').addClass('nk-tb-item');
            $(row).find('td').eq(0).addClass('nk-tb-col');
            $(row).find('td').eq(1).addClass('nk-tb-col');
            $(row).find('td').eq(2).addClass('nk-tb-col tb-col-mb');
            $(row).find('td').eq(3).addClass('nk-tb-col tb-col-lg');
            $(row).find('td').eq(4).addClass('nk-tb-col tb-col-md');
            $(row).find('td').eq(5).addClass('nk-tb-col nk-tb-col-tools'); // re-order table

            table.order([0, 'desc']).draw();
            $('#create-form').trigger('reset');
            $('#modalCreate').modal('hide');
            Swal.fire({
              icon: 'success',
              title: 'Success',
              text: response.message //'Production order created.',
              //footer: '<a href>Why do I have this issue?</a>'

            });
            button.removeAttr('disabled').text('Save');
          } else {
            Swal.fire({
              icon: 'success',
              title: 'Success',
              text: response.message //'Production order created.',
              //footer: '<a href>Why do I have this issue?</a>'

            });
            setTimeout(function () {
              location.reload();
            }, 900);
          }
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
            $('[name="' + key + '"]').parent().append('<span id="' + key + '-error" class="invalid">This field is required.</span>');
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

/***/ 13:
/*!***********************************************************!*\
  !*** multi ./resources/js/admin/production-order-list.js ***!
  \***********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Users/richardholmes/Sites/localhost/jenflow-laravel/resources/js/admin/production-order-list.js */"./resources/js/admin/production-order-list.js");


/***/ })

/******/ });