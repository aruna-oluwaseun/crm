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
/******/ 	return __webpack_require__(__webpack_require__.s = 11);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/admin/customer-list.js":
/*!*********************************************!*\
  !*** ./resources/js/admin/customer-list.js ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(document).ready(function () {
  $('#modalSuspend').on('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    var data_box = $(button).parents('.data-container');
    var id = data_box.data('id');
    var name = data_box.find('.customer-name').val();
    var notes = data_box.find('.customer-notes').val();
    $('#modalSuspend h5.modal-title span').text(name).addClass('text-primary');
    $('#modalSuspend #id').val(id);
    $('#modalSuspend #notes').val(notes);
    setTimeout(function () {
      $('#modalSuspend #notes').focus();
    }, 800);
  });
  /**/

  $(document).on('keyup', '#add-email', function (event) {
    $(this).removeClass('error');

    if ($('#add-email-error').length) {
      $('#add-email-error').remove();
    }

    if ($('#add-email-success').length) {
      $('#add-email-success').remove();
    }

    $('#create-form .submit-btn').removeAttr('disabled');
    $('#password-email').text($(this).val());
  });
  /**
   * Check customer email doesnt exist
   */

  $(document).on('focusout', '#add-email', function (event) {
    // Check the email
    if ($(this).val() != '') {
      $.ajax({
        method: 'GET',
        url: '/check-customer-email',
        data: 'email=' + $('#add-email').val(),
        success: function success(records) {
          if (records > 0) {
            $('#create-form .submit-btn').attr('disabled', 'disabled');
            $('#add-email').addClass('error');
            $('#add-email').parent().append('<span id="add-email-error" class="invalid">This email already exists please change it.</span>');
          } else {
            $('#add-email').addClass('success');
            $('#add-email').parent().append('<small id="add-email-success"  class="text-success">No customer uses this email so you are good to go!</span>');
          }
        },
        error: function error(XHR, textStatus, _error) {
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Your session may have expired, checks cannot be carried out to determine if the already exists.' //footer: '<a href>Why do I have this issue?</a>'

          });
          button.removeAttr('disabled').text('Save');
        }
      });
    }
  });
  /**
   * Suspend user
   */

  $(document).on('submit', '#suspend-form', function (event) {
    event.preventDefault();
    var url = $(this).attr('action');
    var button = $(this).find('.submit-btn');
    var id = $('#modalSuspend #id').val();
    var suspend = '<a href="#" class="btn btn-icon" data-toggle="modal" data-target="#modalSuspend" data-toggle="tooltip" data-placement="top" title="Suspend Customer"><em class="icon ni ni-user-cross-fill"></em></a>';
    var activate = '<a href="#" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Re-activate Customer"><em class="icon ni ni-user-check-fill"></em></a>';
    button.attr('disabled', 'disabled').text('...Please wait');
    $.ajax({
      method: 'POST',
      url: url,
      data: $(this).serialize() + '&_token=' + $('meta[name="csrf-token"]').attr('content'),
      async: false,
      success: function success(data, textStatus, XHR) {
        if (data.success) {
          $('[data-id="' + id + '"]').find('.status-result').html(activate);
          $('[data-id="' + id + '"]').find('.tb-status').removeClass('text-success').addClass('text-danger').text('Suspended');
          $('#modalSuspend').modal('hide');
          Swal.fire({
            icon: 'success',
            title: 'Success',
            text: 'User has been suspended.' //footer: '<a href>Why do I have this issue?</a>'

          });
          button.removeAttr('disabled').text('Save');
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: data.message ? data.message : 'Something went wrong! please re-fresh your page and try again.' //footer: '<a href>Why do I have this issue?</a>'

          });
          button.removeAttr('disabled').text('Save');
        }
      },
      error: function error(XHR, textStatus, _error2) {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Your session may have expired, please re-fresh your page and try again.' //footer: '<a href>Why do I have this issue?</a>'

        });
        button.removeAttr('disabled').text('Save');
      }
    });
  });
  /**
   * Re-activate user
   */

  $(document).on('click', '.btn-trigger', function (event) {
    event.preventDefault();
    var data_box = $(this).parents('.data-container');
    var url = $(this).attr('href');
    var id = data_box.data('id');
    var activate = '<a href="#" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Re-activate Customer"><em class="icon ni ni-user-check-fill"></em></a>';
    $.ajax({
      method: 'POST',
      url: url,
      data: '_method=PUT&status=active&id=' + id + '&_token=' + $('meta[name="csrf-token"]').attr('content'),
      async: false,
      success: function success(data, textStatus, XHR) {
        if (data.success) {
          $('[data-id="' + id + '"]').find('.status-result').html(activate);
          $('[data-id="' + id + '"]').find('.tb-status').removeClass('text-danger').addClass('text-success').text('Active');
          $('#modalSuspend').modal('hide');
          Swal.fire({
            icon: 'success',
            title: 'Success',
            text: 'User has been re-activated.' //footer: '<a href>Why do I have this issue?</a>'

          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: data.message ? data.message : 'Something went wrong! please re-fresh your page and try again.' //footer: '<a href>Why do I have this issue?</a>'

          });
        }
      },
      error: function error(XHR, textStatus, _error3) {
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

/***/ 11:
/*!***************************************************!*\
  !*** multi ./resources/js/admin/customer-list.js ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Users/richardholmes/Sites/localhost/jenflow-laravel/resources/js/admin/customer-list.js */"./resources/js/admin/customer-list.js");


/***/ })

/******/ });