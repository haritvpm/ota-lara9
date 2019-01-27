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
/******/ 	return __webpack_require__(__webpack_require__.s = 41);
/******/ })
/************************************************************************/
/******/ ({

/***/ 41:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(42);


/***/ }),

/***/ 42:
/***/ (function(module, exports) {


///detect brwowser
navigator.sayswho = function () {
  var ua = navigator.userAgent,
      tem,
      M = ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
  if (/trident/i.test(M[1])) {
    tem = /\brv[ :]+(\d+)/g.exec(ua) || [];
    return 'IE ' + (tem[1] || '');
  }
  if (M[1] === 'Chrome') {
    tem = ua.match(/\b(OPR|Edge)\/(\d+)/);
    if (tem != null) return tem.slice(1).join(' ').replace('OPR', 'Opera');
  }
  M = M[2] ? [M[1], M[2]] : [navigator.appName, navigator.appVersion, '-?'];
  if ((tem = ua.match(/version\/(\d+)/i)) != null) M.splice(1, 1, tem[1]);
  return M.join(' ');
}();

var vm = new Vue({
  el: '#app',
  data: {},

  mounted: function mounted() {

    //alert(navigator.sayswho);
    var browser = navigator.sayswho.toLowerCase();

    var x = browser.indexOf('firefox');

    if (-1 != x) {

      var ffver = browser.substr(x + 7).trim();

      if (parseInt(ffver) < 20) {

        //sweetalert is non blocking
        alert('Error!\nPlease update your browser.\nOnly Firefox version 25 or above is supported.');
        //return false;
      }
    }

    if (showloggedinmessage) {
      if (displaynameempty) {

        this.$swal({
          title: 'Enter Name',
          text: 'Please enter your name in the Profile page first.',
          type: 'warning',
          showCancelButton: false,
          confirmButtonColor: '#d33',
          confirmButtonText: 'Change Name',
          allowOutsideClick: false

        }).then(function (result) {

          window.location.href = urlprofile;
        }); // swal

      } else if (isJSorASorSSLevel) {

        this.$swal({
          title: '',
          html: 'Please make sure your title (<strong>' + title + '</strong>) <br>is displayed correctly at the top right corner.'
        });
      } else {

        this.$swal({
          title: 'Welcome',
          text: 'If you are NOT ' + displayname + ', please click Change Name.',
          //type: 'info',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Change Name',
          cancelButtonText: "I'm " + displayname,
          allowOutsideClick: false

        }).then(function (result) {

          //if (result.value) 
          {
            window.location.href = urlprofile;
          }
        }); // swal
      }
    }
  },
  // define methods under the `methods` object
  methods: {}
});

/***/ })

/******/ });