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
/******/ 	return __webpack_require__(__webpack_require__.s = 21);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/admin/helpers.js":
/*!***************************************!*\
  !*** ./resources/js/admin/helpers.js ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

// find
$(document).on('click', '.postcode-btn', function () {
  var button = $(this);
  var text = button.text();
  button.text('...please wait');
  var container = $(this).parents('.address-container');
  var postcode = container.find('.find-postcode').val();
  var result_box = container.find('.show-addresses');

  if (postcode != '') {
    $.ajax({
      method: 'GET',
      url: '/fetch-postcode',
      data: 'postcode=' + postcode,
      success: function success(response) {
        if (response.success) {
          result_box.find('label').text(response.result.length + ' Addresses found');
          var select = '<select class="form-select" id="change-address">';
          $(response.result).each(function (key, result) {
            console.log($(this).postcode + ' OR ' + result.postcode);
            select += '<option data-organisation="' + result.organisation_name + '" data-line-1="' + result.line_1 + '" data-line-2="' + result.line_2 + '" data-line-3="' + result.line_3 + '" data-county="' + result.county + '" data-city="' + result.district + '" data-country="' + result.country + '" data-lat="' + result.latitude + '" data-lng="' + result.longitude + '" data-postcode="' + result.postcode + '" value="' + result.postcode + '">' + result.line_1 + ' ' + result.line_2 + '</option>';
          });
          select += '</option>';
          result_box.find('.form-control-wrap').html(select);
          result_box.find('.form-control-wrap select').select2();
          result_box.show();
          button.text(text);
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: response.error ? response.error : 'An error occurred, please re-fresh and try again.' //footer: '<a href>Why do I have this issue?</a>'

          });
        }
      },
      error: function error(XHR, textStatus, _error) {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'An error occurred, you session may have expired, please re-fresh and try again.' //footer: '<a href>Why do I have this issue?</a>'

        });
        button.text(text);
      }
    });
  } else {
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: 'You need to enter a postcode.' //footer: '<a href>Why do I have this issue?</a>'

    });
    button.text(text);
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

$(document).on('change', '.show-addresses, #billing_id, #delivery_id', function () {
  var container = $(this).parents('.address-container');
  var selected = $(this).find(':selected');
  container.find('.title').val(selected.data('organisation'));
  container.find('.line1').val(selected.data('line-1'));
  container.find('.line2').val(selected.data('line-2'));
  container.find('.line3').val(selected.data('line-3'));
  container.find('.city').val(selected.data('city'));
  container.find('.postcode').val(selected.data('postcode'));
  container.find('.county').val(selected.data('county'));
  container.find('.lat').val(selected.data('lat'));
  container.find('.lng').val(selected.data('lng'));
}); // Delete

$(document).on('click', '.destroy-btn', function (event) {
  event.preventDefault();
  var url = $(this).attr('href');
  var async = $(this).data('async') ? $(this).data('async') : false;
  var self = $(this);
  Swal.fire({
    title: 'Are you sure you want remove this item?',
    text: "You won't be able to revert this!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, remove it!'
  }).then(function (action) {
    if (action.isConfirmed) {
      // send via ajax
      if (async) {
        $.ajax({
          method: 'get',
          url: url,
          success: function success(response) {
            console.log(response);

            if (response.success) {
              if (self.parents('.remove-target').length) {
                self.parents('.remove-target').remove();
                Swal.fire({
                  icon: 'success',
                  title: 'Success',
                  text: 'Item removed.' //footer: '<a href>Why do I have this issue?</a>'

                });
              } else {
                Swal.fire({
                  icon: 'success',
                  title: 'Success',
                  text: 'Item removed, please re-fresh for the item to removed from your view.' //footer: '<a href>Why do I have this issue?</a>'

                });
              }
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: response.message ? response.message : 'An error occurred remove this item please re-fresh and try again.' //footer: '<a href>Why do I have this issue?</a>'

              });
            }
          },
          error: function error(XHR, textStatus, _error2) {
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'An error occurred removing your item, please re-fresh your page and try again.' //footer: '<a href>Why do I have this issue?</a>'

            });
          }
        });
      } else {
        window.location = url;
      }
    }
  });
}); // Proper delete change all that use the above method to this method of delete

$(document).on('click', '.destroy-resource', function (event) {
  event.preventDefault();
  var self = $(this);
  var form = '#' + self.attr('form');
  var message = self.data('message') ? self.data('message') : 'You won\'t be able to revert this!';
  Swal.fire({
    title: 'Are you sure you want remove this item?',
    text: message ? message : "You won't be able to revert this!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, remove it!'
  }).then(function (action) {
    if (action.isConfirmed) {
      // send via ajax
      $(form).submit();
    }
  });
}); // Ready functions

$(document).ready(function () {
  $(document).on('change', '#production-order-due-date', function (event) {
    if ($(this).val() == today) {
      $('#production-order-is-urgent').attr('checked', 'checked');
    }
  });

  if ($('#net-cost').length) {
    $(document).on('change', '#net-cost, #vat-type-id', function (event) {
      var net_cost = parseFloat($('#net-cost').val());
      var vat_value = $('#vat-type-id').find(':selected').data('value'); // Update costings

      if ($('#vat-type-id').length && $('#vat-cost').length && $('#gross-cost').length) {
        var vat = net_cost * vat_value;
        var vat_cost = vat / 100;
        var gross_cost = net_cost + vat_cost;
        vat_cost = vat_cost.toFixed(2);
        gross_cost = gross_cost.toFixed(2);
        $('#vat-cost').val(vat_cost);
        $('#gross-cost').val(gross_cost); // trigger sale change if a product

        if ($('#sale-net-cost').length && $('#sale-net-cost').val() != '') {
          $('#sale-net-cost').trigger('change');
        } // update preview


        if ($('.preview-net-cost').length) {
          $('.preview-net-cost').text('£' + net_cost);
        }

        if ($('.preview-vat-cost').length) {
          $('.preview-vat-cost').text('£' + vat_cost);
        }

        if ($('.preview-gross-cost').length) {
          $('.preview-gross-cost').text('£' + gross_cost);
        }
      }
    });
  }
});
$(document).on('click', '#mark-notification-read', function (event) {
  event.preventDefault();
  event.stopPropagation(); // stop the dropdown hiding

  var self = $(this);
  var url = $(this).attr('href');
  var parent = $(this).parents('.notification-dropdown');
  var body = parent.find('.dropdown-body');
  $.ajax({
    method: 'GET',
    url: url,
    success: function success(response) {
      debug(response);

      if (response.success) {
        var html = '<div class="nk-notification-item dropdown-inner justify-center">';
        html += '<div class="nk-notification-icon">';
        html += '<em class="icon icon-circle bg-success-dim ni ni-happy"></em>';
        html += '</div>';
        html += '<div class="nk-notification-content">';
        html += '<div class="nk-notification-text">You are upto date.</div>';
        html += '<div class="nk-notification-time">No notifications to view.</div>';
        html += '</div>';
        html += '</div>';
        body.html(html);
        parent.find('.nk-dropdown-title').text('No notifications');
        parent.find('.icon-status').removeClass('icon-status-danger').addClass('icon-status-off');
        self.remove();
        /*Swal.fire({
            icon: 'success',
            title: 'Success',
            text: 'Notifications marked as read.',
            //footer: '<a href>Why do I have this issue?</a>'
        });*/
      }
    },
    error: function error(XHR, textStatus, _error3) {
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'An error occurred marking notifications as read.' //footer: '<a href>Why do I have this issue?</a>'

      });
    }
  });
}); // Check for max input value

$(document).on('change focusout', 'input[type=number]', function () {
  // check item cost isn't higher than the max
  var max = $(this).attr('max');

  if ($(this).val() > max) {
    $(this).val(max);
    Swal.fire({
      icon: 'error',
      title: 'Max value exceeded',
      text: 'The max you can enter is ' + max //footer: '<a href>Why do I have this issue?</a>'

    });
  }
});

function debug(obj) {
  var seen = [];
  JSON.stringify(obj, function (key, val) {
    if (val != null && _typeof(val) == "object") {
      if (seen.indexOf(val) >= 0) {
        return;
      }

      seen.push(val);
    }

    return val;
  });
  console.log(seen);
}

function refreshToken() {
  $.ajax({
    method: 'get',
    url: '/refresh-token',
    success: function success(response) {
      $('meta[name="csrf-token"]').attr('content', response.token);
      console.log('New token ' + response.token);
    },
    error: function error() {
      console.log('Failed to refresh token');
    }
  });
}

/***/ }),

/***/ 21:
/*!*********************************************!*\
  !*** multi ./resources/js/admin/helpers.js ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Users/richardholmes/Sites/localhost/jenflow-laravel/resources/js/admin/helpers.js */"./resources/js/admin/helpers.js");


/***/ })

/******/ });