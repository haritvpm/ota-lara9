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
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
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
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 55);
/******/ })
/************************************************************************/
/******/ ({

/***/ 55:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(56);


/***/ }),

/***/ 56:
/***/ (function(module, exports) {


var vm = new Vue({
  el: '#app',
  data: {
    empdesig: '',
    empname: '',
    emppen: '',
    designations: designations

  },

  mounted: function mounted() {},
  watch: {
    emppen: function emppen() {
      this.empname = '';
      this.empdesig = '';
      if (this.emppen.length >= 6) {
        this.asyncFind();
      }
    }

  },

  // define methods under the `methods` object
  methods: {

    asyncFind: _.debounce(function () {
      var app = this;
      //  this.isLoading = true
      // Make a request for a user with a given ID
      app.empname = 'Searching...';
      this.mydelayedsearch(app.emppen.trim());
    }, 500),

    mydelayedsearch: function mydelayedsearch(query) {

      var app = this;
      app.empname = 'Searching...';
      //if(query.length >= 6)
      {
        axios.get(urlajaxpen + '/' + query).then(function (response) {

          if (response.data.pen_names.length) {
            app.empname = response.data.pen_names[0];

            //app.designations = response.data.designations;
            app.empdesig = response.data.pen_names_to_desig[app.empname];
          }
        }).catch(function (response) {
          //alert (JSON.stringify(response.data))    // alerts {"myProp":"Hello"};
          app.empname = 'unknown';
          app.empdesig = '';
        });
      }
    },
    changeSelect: function changeSelect(selectedOption, id) {}
  }
});

/***/ })

/******/ });