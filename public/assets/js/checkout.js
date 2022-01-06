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
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/checkout.js":
/*!**********************************!*\
  !*** ./resources/js/checkout.js ***!
  \**********************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(document).ready(function () {
  // Set stripe public key
  //Stripe.setPublishableKey(stripe_public_key);
  var stripe = Stripe(stripe_public_key);
  var elements = stripe.elements(); // add elements to page

  var card_number = elements.create('cardNumber');
  card_number.mount('#card_number');
  $('#card_number').addClass('form-control');
  var card_expiry = elements.create('cardExpiry');
  card_expiry.mount('#card_expiry');
  $('#card_expiry').addClass('form-control');
  var card_cvc = elements.create('cardCvc');
  card_cvc.mount('#card_cvc');
  $('#card_cvc').addClass('form-control');
  card_number.addEventListener('change', function (event) {
    if (event.error) {
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: event.error.message
      });
    }
  });
  card_expiry.addEventListener('change', function (event) {
    if (event.error) {
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: event.error.message
      });
    }
  });
  card_cvc.addEventListener('change', function (event) {
    if (event.error) {
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: event.error.message
      });
    }
  }); // Visual form validation

  $(document).on('keyup focusout blur change', '#pay-form .required', function () {
    if ($('#terms-accept').is(':checked') && $('[name="card-owner"]').val() !== '') {
      $('#pay-form button').removeAttr('disabled');
    } else {
      $('#pay-form button').attr('disabled', 'disabled');
    }
  }); //console.log('City = '+city +' postoced = '+ postcode);
  // Handle checkout button

  $('#pay-form button').click(function (e) {
    // Check progress
    if (window.checkoutInProgress) {
      return false;
    } // Set helper variable


    window.checkoutInProgress = true; // Change button

    $('#pay-form button').html('Please wait...');
    $('#pay-form button').attr('disabled', 'disabled'); // Get card details

    var ccOwner = $('.card-owner').val();
    stripe.confirmCardPayment(stripe_client_secret, {
      payment_method: {
        card: card_number,
        billing_details: {
          name: ccOwner,
          address: {
            city: city ? city : null,
            country: country ? country : null,
            // country code
            line1: line1 ? line1 : null,
            line2: line2 ? line2 : null,
            postal_code: postcode ? postcode : null,
            state: county ? county : null
          }
        }
      },
      receipt_email: receipt_email ? receipt_email : null
    }).then(function (result) {
      if (result.error) {
        // Show error
        // check payment intent isn't already paid.
        if (result.error.payment_intent.status == 'succeeded') {
          Swal.fire({
            icon: 'warning',
            title: 'Oops...',
            text: 'This payment has already been processed, please wait while we check this...'
          });
          setTimeout(function () {
            postPayment();
          }, 1200);
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: result.error.message
          });
          resetCheckoutProgress();
        }
      } else {
        // The payment has succeeded.
        postPayment();
      }
    });
  });
});

function postPayment() {
  $.ajax({
    method: 'POST',
    url: url,
    data: {
      'payment_id': payment_id,
      'invoice_id': invoice_id,
      '_token': $('[name="csrf-token"]').attr('content')
    },
    success: function success(response) {
      if (!response.success) {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: response.message ? response.message : 'Payment failed, please try another payment method.'
        });
        resetCheckoutProgress();
      } else {
        window.location = '/invoices/payment-response/' + response.payment_ref;
      }
    },
    error: function error(XHR, textStatus, _error) {
      var message;

      if (XHR.status === 422) {
        var response = XHR.responseJSON;
        var errors = response.errors;
        message = 'You have errors in your form.';
        $.each(errors, function (key, value) {
          //$('#edit-form [name="'+ key +'"]').parent().append('<span id="'+ key +'-error" class="invalid">This field is required.</span>');
          message += value;
        });
      }

      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: message ? message : 'An error occurred checking over the payment, please reload this page to see if payment was successful.' //footer: '<a href>Why do I have this issue?</a>'

      });
      resetCheckoutProgress();
    }
  });
}
/*
 *	Reset progress
 */


function resetCheckoutProgress() {
  // Set helper variable
  window.checkoutInProgress = false; // Change button

  $('#pay-form button').html('Pay &amp; place order');
  $('#pay-form button').removeAttr('disabled');
}

/***/ }),

/***/ 1:
/*!****************************************!*\
  !*** multi ./resources/js/checkout.js ***!
  \****************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Users/richardholmes/Sites/localhost/jenflow-laravel/resources/js/checkout.js */"./resources/js/checkout.js");


/***/ })

/******/ });