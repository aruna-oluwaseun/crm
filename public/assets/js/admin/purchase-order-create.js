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
/******/ 	return __webpack_require__(__webpack_require__.s = 22);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/admin/purchase-order-create.js":
/*!*****************************************************!*\
  !*** ./resources/js/admin/purchase-order-create.js ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(document).ready(function () {
  $(document).on('change', 'input[name="email"]', function (event) {
    $('#quote-email').text($(this).val());
  });

  if ($('#update-costings').length) {
    setTimeout(function () {
      $('.qty').trigger('change');
    }, 800);
  }

  if (load_supplier_items) {
    setTimeout(function () {
      $('[name="supplier_id"]').val(load_supplier_items).trigger('change');
    }, 800);
  }

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
  }); // Sale order form

  $(document).on('submit', '#create-purchase-form', function (e) {
    if ($('.attendee').length) {
      var counter = 0;
      $('.attendee').each(function () {
        if (!$(this).val()) {
          counter++;
        }
      });

      if (counter) {
        e.preventDefault();
        Swal.fire({
          icon: 'info',
          title: 'Enter Attendees',
          text: 'Please enter all attendee names.' //footer: '<a href>Why do I have this issue?</a>'

        });
      }
    }
  });
  $(document).on('change', '[name="supplier_id"]', function () {
    var selected = $(this).find(':selected'); // get customer addresses

    $.ajax({
      method: 'get',
      url: '/supplier/products/' + selected.val(),
      success: function success(response) {
        if (response.success) {
          var products = '<option>Select product</option>';
          $.each(response.data, function (key, value) {
            products += '<option value="' + value.id + '" data-weight="' + value.weight + '" data-unit-of-measure-id="' + value.unit_of_measure_id + '" data-unit-of-measure-title="' + (value.unit_of_measure.title ? value.unit_of_measure.title : '') + '" data-weight-kg="' + value.weight_kg + '" data-cost="' + value.pivot.cost_to_us + '" data-vat-type-id="' + value.pivot.vat_type_id + '">';
            products += value.title + ' - (' + value.pivot.code + ')';
            products += '</option>';
          });
          $('#products-list .select-product').each(function (key, value) {
            if ($(this).hasClass('select2-hidden-accessible')) {
              $(this).select2('destroy');
            }

            $(this).html(products);
            $(this).select2();
            $(this).parents('td').find('.product-alert').hide();
            $(this).parents('td').find('.product-row').show();
          });
          $('#product-prototype .select-product').html(products);
          $('#product-prototype .product-alert').hide();
          $('#product-prototype .product-row').show();
        } else {
          Swal.fire({
            icon: 'info',
            title: 'No products',
            text: 'The selected supplier has no products linked to them.' //footer: '<a href>Why do I have this issue?</a>'

          });
        }
      },
      error: function error() {
        console.log('Failed to fetch products');
      }
    });
  }); // Costings

  $(document).on('change focusout', '.item-cost, .vat-type, .qty', function (event) {
    event.stopImmediatePropagation();
    var container = $(this).parents('tr');
    var row_id = container.data('id');
    var product_row = container.find('.select-product').find(':selected');
    var selected = product_row.val();
    var cost_in = product_row.data('cost') ? product_row.data('cost') : 0;
    var weight = product_row.data('weight');
    var unit_of_measure = product_row.data('unit-of-measure-title');
    var item_cost = $(container).find('.item-cost');
    var vat_type = $(container).find('.vat-type');
    var net = $(container).find('.net-cost');
    var gross_cost = $(container).find('.gross-cost');
    var vat_cost = $(container).find('.vat-cost');
    var qty = $(container).find('.qty').val();
    var weight_container = $('.show-weight-' + row_id);

    if (weight) {
      var total_product_weight = qty * weight;
      weight_container.find('td label span').text(total_product_weight + ' ' + unit_of_measure);
    }

    if (item_cost.val() == '') {
      item_cost.val(0);
    }

    var vat_value = vat_type.find(':selected').data('value');
    var net_cost = parseFloat(item_cost.val()) * qty;
    net.val(net_cost);
    var vat = net_cost * vat_value / 100;
    vat_cost.val(vat.toFixed(2));
    var gross = net_cost + vat;
    gross_cost.val(gross.toFixed(2));
    calculate_totals();
  });
  /*
   * Shipping cost
   */

  $('#add-shipping-cost,#add-shipping-vat-id').on('change', function () {
    calculate_totals();
  }); // Populate form data

  $(document).on('change', '.select-product', function (event) {
    event.stopImmediatePropagation();
    var row_id = $(this).parents('.tb-tnx-item').data('id');
    var product_row = $(this).find(':selected');
    var selected = product_row.val();
    var cost_in = product_row.data('cost') ? product_row.data('cost') : 0;
    var vat_type_id = product_row.data('vat-type-id') ? product_row.data('vat-type-id') : false;
    var weight = product_row.data('weight');
    var unit_of_measure = product_row.data('unit-of-measure-title');
    var container = $(this).parents('tr');
    var item_cost = $(container).find('.item-cost');
    var net_cost = $(container).find('.net-cost');
    var vat_type = $(container).find('.vat-type');
    var gross_cost = $(container).find('.gross-cost');
    var qty = $(container).find('.qty');
    var weight_container = $('.show-weight-' + row_id);

    if (vat_type_id) {
      vat_type.val(vat_type_id).trigger('change');
    }

    item_cost.val(cost_in);
    net_cost.val(0);
    gross_cost.val(0);

    if (qty.val() == '') {
      qty.val(1);
    }

    if (weight) {
      var total_product_weight = qty.val() * weight;
      weight_container.find('td label span').text(total_product_weight + ' ' + unit_of_measure);
    }

    qty.trigger('change');
  });
  /*
   *  Add a new product to the list
   */

  $('#add-product').on('click', function (event) {
    event.preventDefault();
    var clone = $('#product-prototype tbody').html();
    var id = new Date().getTime();
    clone = clone.replace(/{REPLACE_ID}/g, id);
    $('#products-list tbody').append(clone);
    $('#products-list tbody').find('.select-product:not(.select2)').select2();
    $('#products-list tbody').find('.vat-type:not(.select2)').select2();
    $('#products-list tbody').find('.checkbox-item:last').removeAttr('disabled');
    calculate_totals();
  });
});

function calculate_totals() {
  var subtotal = 0;
  var vat = 0;
  var gross = 0;
  var shipping = 0;
  var shipping_vat = 0;
  $('#products-list tbody tr.tb-tnx-item').each(function () {
    subtotal += parseFloat($(this).find('.net-cost').val());
    vat += parseFloat($(this).find('.vat-cost').val());
  });
  shipping = parseFloat($('[name="shipping_cost"]').val());
  shipping_vat = $('#add-shipping-vat-id').find(':selected').data('value');
  shipping_vat = shipping_vat / 100 * shipping;
  vat += shipping_vat;
  gross = parseFloat(subtotal) + parseFloat(vat) + shipping;
  $('#show-total-net span').text(subtotal.toFixed(2));
  $('#show-total-vat span').text(vat.toFixed(2));
  $('#show-total-gross span').text(gross.toFixed(2));
}

/***/ }),

/***/ 22:
/*!***********************************************************!*\
  !*** multi ./resources/js/admin/purchase-order-create.js ***!
  \***********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Users/richardholmes/Sites/localhost/jenflow-laravel/resources/js/admin/purchase-order-create.js */"./resources/js/admin/purchase-order-create.js");


/***/ })

/******/ });