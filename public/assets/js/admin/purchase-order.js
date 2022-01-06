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
/******/ 	return __webpack_require__(__webpack_require__.s = 23);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/admin/purchase-order.js":
/*!**********************************************!*\
  !*** ./resources/js/admin/purchase-order.js ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(document).ready(function () {
  $(document).on('click', '.checkbox-item-all, .checkbox-item', function () {
    if ($(this).hasClass('checkbox-item-all')) {
      if ($(this).is(':checked')) {
        // uncheck all
        $('#products-list input.checkbox-item:not(:disabled)').prop('checked', true);
      } else {
        // check all
        $('#products-list input.checkbox-item:not(:disabled)').prop('checked', false);
      }
    }

    var checked = $('#products-list input.checkbox-item:checked').length;

    if (checked) {
      $('#remove-items-btn').removeClass('disabled').removeAttr('disabled');
    } else {
      $('#remove-items-btn').addClass('disabled').attr('disabled', 'disabled');
    }
  });
  $('#remove-items-btn').on('click', function (event) {
    event.preventDefault();
    Swal.fire({
      title: 'Are you sure you want remove checked items?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, remove it!'
    }).then(function (action) {
      if (action.isConfirmed) {
        $('#products-list input.checkbox-item').each(function () {
          if ($(this).is(':checked')) {
            $(this).parents('tr').remove();
          }
        });
      }
    });
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
  }); // copy address to shipping

  $('.copy-address-to-shipping').on('click', function (e) {
    e.preventDefault();
    var parent = $(this).parents('.address'); //#shipping-address-details
    //#billing-address-details

    $('[name="delivery_address_data[title]"]').val(parent.find('[name="billing_address_data[title]"]').val());
    $('[name="delivery_address_data[line1]"]').val(parent.find('[name="billing_address_data[line1]"]').val());
    $('[name="delivery_address_data[line2]"]').val(parent.find('[name="billing_address_data[line2]"]').val());
    $('[name="delivery_address_data[line3]"]').val(parent.find('[name="billing_address_data[line3]"]').val());
    $('[name="delivery_address_data[city]"]').val(parent.find('[name="billing_address_data[city]"]').val());
    $('[name="delivery_address_data[postcode]"]').val(parent.find('[name="billing_address_data[postcode]"]').val());
    $('[name="delivery_address_data[county]"]').val(parent.find('[name="billing_address_data[county]"]').val());
    $('[name="delivery_address_data[country]"]').val(parent.find('[name="billing_address_data[country]"]').val());
    $('[name="delivery_address_data[lat]"]').val(parent.find('[name="billing_address_data[lat]"]').val());
    $('[name="delivery_address_data[lng]"]').val(parent.find('[name="billing_address_data[lng]"]').val());
  });
  $('.copy-address-to-billing').on('click', function (e) {
    e.preventDefault();
    var parent = $(this).parents('.address'); //#shipping-address-details
    //#billing-address-details

    $('[name="billing_address_data[title]"]').val(parent.find('[name="delivery_address_data[title]"]').val());
    $('[name="billing_address_data[line1]"]').val(parent.find('[name="delivery_address_data[line1]"]').val());
    $('[name="billing_address_data[line2]"]').val(parent.find('[name="delivery_address_data[line2]"]').val());
    $('[name="billing_address_data[line3]"]').val(parent.find('[name="delivery_address_data[line3]"]').val());
    $('[name="billing_address_data[city]"]').val(parent.find('[name="delivery_address_data[city]"]').val());
    $('[name="billing_address_data[postcode]"]').val(parent.find('[name="delivery_address_data[postcode]"]').val());
    $('[name="billing_address_data[county]"]').val(parent.find('[name="delivery_address_data[county]"]').val());
    $('[name="billing_address_data[country]"]').val(parent.find('[name="delivery_address_data[country]"]').val());
    $('[name="billing_address_data[lat]"]').val(parent.find('[name="delivery_address_data[lat]"]').val());
    $('[name="billing_address_data[lng]"]').val(parent.find('[name="delivery_address_data[lng]"]').val());
  }); // change billing address

  $('#change-billing-address').click(function (e) {
    e.preventDefault();
    $('.new-billing-address').toggle();
  }); // change billing address

  $('#change-delivery-address').click(function (e) {
    e.preventDefault();
    $('.new-delivery-address').toggle();
  });
  $(document).on('change', '#add-none-stock-item', function () {
    if ($(this).is(':checked')) {
      $('#add-product-id').attr('disabled', 'disabled');
      $('#select-product').hide();
      $('#add-product-title').removeAttr('disabled');
      $('#add-product-code').removeAttr('disabled');
      $('#typein-product').show();
      $('#add-item-weight').show();
      $('#add-qty').removeAttr('readonly');
    } else {
      $('#add-product-id').removeAttr('disabled');
      $('#select-product').show();
      $('#add-product-title').attr('disabled', 'disabled');
      $('#add-product-code').attr('disabled', 'disabled');
      $('#typein-product').hide();
      $('#add-item-weight').hide();
      $('#show-weight-box span').val($('#add-original-weight-value').val() + ' ' + $('#add-original-unit-of-measure').val());
    }
  }); // Costings

  $(document).on('change focusout', '#add-item-cost, #add-vat-type-id, #add-qty', function (event) {
    event.stopImmediatePropagation();
    var product_row = $('#add-product-id').find(':selected');
    var selected = product_row.val();
    var cost_in = product_row.data('cost') ? product_row.data('cost') : 0;
    var weight = product_row.data('weight');
    var unit_of_measure = product_row.data('unit-of-measure-title');
    var item_cost = $('#add-item-cost');
    var vat_value = $('#add-vat-type-id').find(':selected').data('value');
    var net = $('#add-net-cost');
    var gross_cost = $('#add-gross-cost');
    var vat_cost = $('#add-vat-cost');
    var qty = $('#add-qty').val();
    var weight_container = $('#show-weight-box span');
    $('#add-original-weight-value').val(0);
    $('#add-original-unit-of-measure').val('');

    if (weight) {
      $('#add-original-weight-value').val(weight);
      $('#add-original-unit-of-measure').val(unit_of_measure);
      var total_product_weight = qty * weight;
      weight_container.text(total_product_weight + ' ' + unit_of_measure);
    }

    if (item_cost.val() == '') {
      item_cost.val(0);
    }

    var net_cost = parseFloat(item_cost.val()) * qty;
    net.val(net_cost);
    var vat = net_cost * vat_value / 100;
    vat_cost.val(vat.toFixed(2));
    var gross = net_cost + vat;
    gross_cost.val(gross.toFixed(2));
  }); // Populate form data

  $(document).on('change', '#add-product-id', function (event) {
    event.stopImmediatePropagation();
    var product_row = $('#add-product-id').find(':selected');
    var selected = product_row.val();
    var cost_in = product_row.data('cost') ? product_row.data('cost') : 0;
    var weight = product_row.data('weight');
    var unit_of_measure = product_row.data('unit-of-measure-title');
    var item_cost = $('#add-item-cost');
    var vat_value = $('#add-vat-type-id').find(':selected').data('value');
    var net = $('#add-net-cost');
    var gross_cost = $('#add-gross-cost');
    var vat_cost = $('#add-vat-cost');
    var qty = $('#add-qty').val();
    var weight_container = $('#show-weight-box span');
    item_cost.val(cost_in);
    net.val(0);
    gross_cost.val(0);

    if (qty == '') {
      qty.val(1);
    }

    if (weight) {
      var total_product_weight = qty * weight;
      weight_container.text(total_product_weight + ' ' + unit_of_measure);
    }

    var net_cost = parseFloat(item_cost.val()) * qty;
    net.val(net_cost);
    var vat = net_cost * vat_value / 100;
    vat_cost.val(vat.toFixed(2));
    var gross = net_cost + vat;
    gross_cost.val(gross.toFixed(2));
  });
  $(document).on('click', '#dispatch-is-collection', function () {
    if ($(this).is(':checked')) {
      $('.collection-details').show();
      $('.shipping-details').hide();
      $('#dispatch-courier-title').attr('disabled', 'disabled');
    } else {
      $('.collection-details').hide();
      $('.shipping-details').show();
      $('#dispatch-courier-title').removeAttr('disabled');
    }
  });
  $(document).on('change', '#dispatch-courier-id', function () {
    var selected = $(this).find(':selected').val();

    if (selected == 'new') {
      $('#dispatch-courier-title').removeAttr('disabled');
      $('#dispatch-new-courier').show();
    } else {
      $('#dispatch-courier-title').attr('disabled', 'disabled');
      $('#dispatch-new-courier').hide();
    }
  });
  /*
   * Get what we want to dispatch
   */

  $('#modalDispatch').on('shown.bs.modal', function () {
    $('.loading').show();
    $('#load-dispatch-items').hide();
    $.ajax({
      url: $('#dispatch-items-form').attr('action'),
      method: 'POST',
      data: $('#dispatch-items-form').serialize() + '&_token=' + $('meta[name="csrf-token"]').attr('content'),
      success: function success(response) {
        if (response.success) {
          var html = '';
          $.each(response.data, function (key, value) {
            if (value.qty === 0) {
              // All items have shipped
              html += '<tr><td colspan="3">' + value.product_title + ' cannot be dispatched / collected, all items have been dispatched / collected</td></tr>';
            } else {
              html += '<tr><td>' + value.product_title + '</td><td>' + value.sent + '</td><td><input name="dispatch_items[' + value.purchase_order_item_id + ']" type="number" min="1" max="' + value.qty + '" class="form-control" value="' + value.qty + '"></td></tr>';
            }
          });
          $('#load-dispatch-items tbody').html(html);
          $('.loading').hide();
          $('#load-dispatch-items').show();
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: response.message ? response.message : 'We could not retrieve the items you wish to mark as dispatched.' //footer: '<a href>Why do I have this issue?</a>'

          });
        }
      },
      error: function error(XHR, textStatus, _error) {
        if (XHR.status === 422) {
          var response = XHR.responseJSON;
          var errors = response.errors;
          var message = 'You have errors in your form.';
          $.each(errors, function (key, value) {
            $('#edit-form [name="' + key + '"]').parent().append('<span id="' + key + '-error" class="invalid">This field is required.</span>');
            message += value;
          });
        }

        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: message ? message : 'Your session may have expired, please re-fresh your page and try again.' //footer: '<a href>Why do I have this issue?</a>'

        });
      }
    });
  });
});

/***/ }),

/***/ 23:
/*!****************************************************!*\
  !*** multi ./resources/js/admin/purchase-order.js ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Users/richardholmes/Sites/localhost/jenflow-laravel/resources/js/admin/purchase-order.js */"./resources/js/admin/purchase-order.js");


/***/ })

/******/ });