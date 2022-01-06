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
/******/ 	return __webpack_require__(__webpack_require__.s = 3);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/admin/editors.js":
/*!***************************************!*\
  !*** ./resources/js/admin/editors.js ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


!function (NioApp, $) {
  "use strict"; // SummerNote Init @v1.0

  NioApp.SummerNote = function () {
    var _basic = '.summernote-basic';

    if ($(_basic).exists()) {
      $(_basic).each(function () {
        $(this).summernote({
          placeholder: 'Hello stand alone ui',
          tabsize: 2,
          height: 120,
          toolbar: [['style', ['style']], ['font', ['bold', 'underline', 'strikethrough', 'clear']], ['font', ['superscript', 'subscript']], ['color', ['color']], ['fontsize', ['fontsize', 'height']], ['para', ['ul', 'ol', 'paragraph']], ['table', ['table']], ['insert', ['link', 'picture', 'video']], ['view', ['fullscreen', 'codeview', 'help']]]
        });
      });
    }

    var _minimal = '.summernote-minimal';

    if ($(_minimal).exists()) {
      $(_minimal).each(function () {
        $(this).summernote({
          placeholder: 'Hello stand alone ui',
          tabsize: 2,
          height: 120,
          toolbar: [['style', ['style']], ['font', ['bold', 'underline', 'clear']], ['para', ['ul', 'ol', 'paragraph']], ['table', ['table']], ['view', ['fullscreen']]]
        });
      });
    }
  }; // Tinymce Init @v1.0


  NioApp.Tinymce = function () {
    var _tinymce_basic = '.tinymce-basic';

    if ($(_tinymce_basic).exists()) {
      tinymce.init({
        selector: _tinymce_basic,
        content_css: true,
        skin: false,
        branding: false
      });
    }

    var _tinymce_menubar = '.tinymce-menubar';

    if ($(_tinymce_menubar).exists()) {
      tinymce.init({
        selector: _tinymce_menubar,
        content_css: true,
        skin: false,
        branding: false,
        toolbar: false
      });
    }

    var _tinymce_toolbar = '.tinymce-toolbar';

    if ($(_tinymce_toolbar).exists()) {
      tinymce.init({
        selector: _tinymce_toolbar,
        content_css: true,
        skin: false,
        branding: false,
        menubar: false
      });
    }

    var _tinymce_inline = '.tinymce-inline';

    if ($(_tinymce_inline).exists()) {
      tinymce.init({
        selector: _tinymce_inline,
        content_css: false,
        skin: false,
        branding: false,
        menubar: false,
        inline: true,
        toolbar: ['undo redo | bold italic underline | fontselect fontsizeselect', 'forecolor backcolor | alignleft aligncenter alignright alignfull | numlist bullist outdent indent']
      });
    }
  }; // Quill Init @v1.0


  NioApp.Quill = function () {
    var _basic = '.quill-basic';

    if ($(_basic).exists()) {
      $(_basic).each(function () {
        var toolbarOptions = [['bold', 'italic', 'underline', 'strike'], // toggled buttons
        ['blockquote', 'code-block'], [{
          'list': 'ordered'
        }, {
          'list': 'bullet'
        }], [{
          'script': 'sub'
        }, {
          'script': 'super'
        }], // superscript/subscript
        [{
          'indent': '-1'
        }, {
          'indent': '+1'
        }], // outdent/indent
        [{
          'header': [1, 2, 3, 4, 5, 6]
        }], [{
          'color': []
        }, {
          'background': []
        }], // dropdown with defaults from theme
        [{
          'font': []
        }], [{
          'align': []
        }], ['clean'] // remove formatting button
        ];
        var quill = new Quill(this, {
          modules: {
            toolbar: toolbarOptions
          },
          theme: 'snow'
        });
      });
    }

    var _minimal = '.quill-minimal';

    if ($(_minimal).exists()) {
      $(_minimal).each(function () {
        var toolbarOptions = [['bold', 'italic', 'underline'], // toggled buttons
        ['blockquote', {
          'list': 'bullet'
        }], [{
          'header': 1
        }, {
          'header': 2
        }, {
          'header': [3, 4, 5, 6, false]
        }], [{
          'align': []
        }], ['clean'] // remove formatting button
        ];
        var quill = new Quill(this, {
          modules: {
            toolbar: toolbarOptions
          },
          theme: 'snow'
        });
      });
    }
  }; // Editor Init @v1


  NioApp.EditorInit = function () {
    NioApp.SummerNote();
    NioApp.Tinymce();
    NioApp.Quill();
  };

  NioApp.coms.docReady.push(NioApp.EditorInit);
}(NioApp, jQuery);

/***/ }),

/***/ 3:
/*!*********************************************!*\
  !*** multi ./resources/js/admin/editors.js ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Users/richardholmes/Sites/localhost/jenflow-laravel/resources/js/admin/editors.js */"./resources/js/admin/editors.js");


/***/ })

/******/ });