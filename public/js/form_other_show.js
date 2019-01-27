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
/******/ 	return __webpack_require__(__webpack_require__.s = 53);
/******/ })
/************************************************************************/
/******/ ({

/***/ 53:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(54);


/***/ }),

/***/ 54:
/***/ (function(module, exports) {

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

Vue.use(VueSweetAlert.default);

var vm = new Vue({
    el: '#app',
    data: {},

    mounted: function mounted() {},
    // define methods under the `methods` object
    methods: {
        forwardClick: function forwardClick() {

            var self = this;
            this.$swal(_defineProperty({
                titleText: 'Forward form to:',
                input: 'select',
                type: 'question',
                confirmButtonText: '<i class="fa fa-mail-forward"></i> Forward',
                inputOptions: forwardarray,
                //inputPlaceholder: 'Select whom to forward to',
                inputValue: initalvalue,
                // inputclass:'form-control',
                showCancelButton: true,
                showCloseButton: true,
                allowOutsideClick: false,
                allowEnterKey: false,
                focusCancel: true,
                // animation : false,
                inputValidator: function inputValidator(value) {
                    return new Promise(function (resolve, reject) {
                        if (value != '') {
                            resolve();
                        } else {
                            reject('You need to select a person to forward to!');
                        }
                    });
                },
                showLoaderOnConfirm: true,
                preConfirm: function preConfirm(value) {
                    return new Promise(function (resolve, reject) {

                        var obj = { 'owner': value };

                        axios.put(urlformforward + "/" + formid, obj).then(function (response) {

                            if (response.data.result) {
                                resolve();
                            } else {
                                reject('Error, cannot forward');
                            }
                        }).catch(function (error) {
                            console.log(error.response);
                            reject(error.response.data);
                        });
                    });
                }
            }, 'allowOutsideClick', false)).then(function (result) {
                self.$swal({
                    type: 'success',
                    html: 'Forwarded!',
                    timer: 1500,
                    useRejections: false
                }).then(function (result) {
                    window.location.href = urlredirect;
                });
            });
        },
        submitClick: function submitClick() {
            var self = this;
            this.$swal({
                titleText: 'Submit to Accounts:',
                text: 'Are you sure you want to submit this form to Accounts?',
                type: 'question',
                confirmButtonText: '<i class="fa fa-envelope"></i> Submit',
                allowEnterKey: false,
                showCancelButton: true,
                showCloseButton: true,
                focusCancel: true,
                showLoaderOnConfirm: true,
                preConfirm: function preConfirm() {
                    return new Promise(function (resolve, reject) {

                        var obj = { 'owner': 'admin' };

                        axios.put(urlformsubmittoaccounts + "/" + formid, obj).then(function (response) {

                            if (response.data.result) {
                                resolve();
                            } else {
                                reject('Error, cannot submit');
                            }
                        }).catch(function (error) {
                            console.log(error.response);
                            reject(error.response.data);
                        });
                    });
                }

            }).then(function (result) {
                self.$swal({
                    type: 'success',
                    html: 'Submitted to Accounts!',
                    timer: 1500,
                    useRejections: false //prevent exception due to uncatched timer event
                }).then(function (result) {
                    window.location.href = urlredirect;
                });
            });
        },
        sendbackClick: function sendbackClick() {
            var self = this;
            this.$swal({
                titleText: 'Send back to the form creator?',
                input: 'textarea',
                type: 'question',
                inputPlaceholder: 'Enter reason if any',
                inputValue: remarks,
                confirmButtonText: '<i class="fa fa-reply"></i> Send back',
                allowEnterKey: false,
                showCancelButton: true,
                showCloseButton: true,
                focusCancel: true,
                showLoaderOnConfirm: true,
                preConfirm: function preConfirm(text) {
                    return new Promise(function (resolve, reject) {

                        var obj = { 'remarks': text };

                        axios.put(urlformsendback + "/" + formid, obj).then(function (response) {

                            if (response.data.result) {
                                resolve();
                            } else {
                                reject('Error, cannot send back');
                            }
                        }).catch(function (error) {
                            console.log(error.response);
                            reject(error.response.data);
                        });
                    });
                }

            }).then(function (result) {
                self.$swal({
                    type: 'success',
                    html: 'Sent back!',
                    timer: 1500,
                    useRejections: false
                }).then(function (result) {
                    window.location.href = urlredirect;
                });
            });
        }
    }
});

/***/ })

/******/ });