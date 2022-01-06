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
/******/ 	return __webpack_require__(__webpack_require__.s = 16);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/admin/product-create.js":
/*!**********************************************!*\
  !*** ./resources/js/admin/product-create.js ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(document).ready(function () {
  // Keywords
  $('[name=keywords]').tagify(); // slug

  $(document).on('keyup', '[name="title"]', function (event) {
    var title = $(this).val();
    setTimeout(function () {
      var slug = title.toLowerCase().replace(/ /g, '-').replace(/[-]+/g, '-').replace(/[^\w-]+/g, '');
      $('[name="slug"]').val(slug);
    }, 400);
  }); // Training

  $(document).on('change', '#is-training', function (event) {
    if ($(this).is(':checked')) {
      $('#assessment-product').show();
      $('#free-shipping').prop('checked', 'checked');
    } else {
      $('#assessment-product').hide();
      $('#free-shipping').prop('checked', false);
    }
  }); // Pallet or Box

  $(document).on('change', '#is-packaging', function (event) {
    if ($(this).is(':checked')) {
      $('#packaging-options').show();
      $('#product-options').hide();
    } else {
      $('#packaging-options').hide();
      $('#product-options').show();
    }
  });
  $(document).on('change', '#is-shipping-box', function (event) {
    if ($(this).is(':checked')) {
      $('#is-shipping-pallet').prop('checked', false);
    }
  });
  $(document).on('change', '#is-shipping-pallet', function (event) {
    if ($(this).is(':checked')) {
      $('#is-shipping-box').prop('checked', false);
    }
  }); // summernote editor

  if ($('.summernote-minimal').length) {
    $('.summernote-minimal').summernote({
      placeholder: 'Description',
      tabsize: 2,
      height: 120,
      toolbar: [['style', ['style']], ['font', ['bold', 'underline', 'clear']], ['para', ['ul', 'ol', 'paragraph']], ['table', ['table']], ['view', ['fullscreen']]]
    });
  } // Sale costings


  if ($('#sale-net-cost').length) {
    setTimeout(function () {
      $('#sale-net-cost').trigger('change');
    }, 400);
    $(document).on('change keyup', '#sale-net-cost, #vat-type-id', function (event) {
      var net_cost = parseFloat($('#sale-net-cost').val());
      var vat_value = $('#vat-type-id').find(':selected').data('value'); // Update costings

      if ($('#sale-net-cost').val() != '') {
        if ($('#vat-type-id').length && $('#sale-vat-cost').length && $('#sale-gross-cost').length) {
          var vat = net_cost * vat_value;
          var vat_cost = vat / 100;
          var gross_cost = net_cost + vat_cost;
          vat_cost = vat_cost.toFixed(2);
          gross_cost = gross_cost.toFixed(2);
          $('#sale-vat-cost').val(vat_cost);
          $('#sale-gross-cost').val(gross_cost); // calculate saving

          var cost_net = parseFloat($('#net-cost').val());
          var sale_net = parseFloat($('#sale-net-cost').val());
          var saving = sale_net / cost_net * 100;
          saving = 100 - saving;
          $('#sale-saving mark').text(saving.toFixed(2) + '%').removeClass('text-danger');
          $('#sale-saving').show();

          if (saving < 0) {
            $('#sale-saving mark').text(saving.toFixed(2) + '% this is not a saving').addClass('text-danger');
          }
        }
      } else {
        $('#sale-saving').hide();
        $('#sale-vat-cost').val('');
        $('#sale-gross-cost').val('');
      }
    });
  } // Deposit allowed


  $(document).on('change', '#deposit-allowed', function () {
    if ($(this).is(':checked')) {
      $('#deposit-costings').show();
    } else {
      $('#deposit-costings').hide();
    }
  }); // Update costings

  if ($('#deposit-net-cost').length) {
    $(document).on('change', '#deposit-net-cost, #vat-type-id', function (event) {
      var net_cost = parseFloat($('#deposit-net-cost').val());
      var vat_value = $('#vat-type-id').find(':selected').data('value'); // Update costings

      if ($('#deposit-net-cost').val() != '') {
        if ($('#vat-type-id').length && $('#deposit-vat-cost').length && $('#deposit-gross-cost').length) {
          var vat = net_cost * vat_value;
          var vat_cost = vat / 100;
          var gross_cost = net_cost + vat_cost;
          vat_cost = vat_cost.toFixed(2);
          gross_cost = gross_cost.toFixed(2);
          $('#deposit-vat-cost').val(vat_cost);
          $('#deposit-gross-cost').val(gross_cost);
        }
      } else {
        $('#sale-saving').hide();
        $('#sale-vat-cost').val('');
        $('#sale-gross-cost').val('');
      }
    });
  }
});

/***/ }),

/***/ 16:
/*!****************************************************!*\
  !*** multi ./resources/js/admin/product-create.js ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Users/richardholmes/Sites/localhost/jenflow-laravel/resources/js/admin/product-create.js */"./resources/js/admin/product-create.js");


/***/ })

/******/ });