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
/******/ 	return __webpack_require__(__webpack_require__.s = 49);
/******/ })
/************************************************************************/
/******/ ({

/***/ 49:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(50);


/***/ }),

/***/ 50:
/***/ (function(module, exports) {

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

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

var ua = navigator.userAgent.toLowerCase();
var isWinXP = ua.indexOf('windows nt 5.1') > 0;

Vue.use(VueSweetAlert.default);

var vm = new Vue({
    el: '#app',
    data: {
        agreechecked: false,
        approvaltext: malkla + ' കേരള നിയമസഭയുടെ  ' + sessionnumber + '-ാം സമ്മേളനത്തോടനുബന്ധിച്ച് അധികജോലിക്കു നിയോഗിക്കപ്പെട്ട ജീവനക്കാർക്ക്  ഓവർടൈം അലവൻസ് അനുവദിക്കുന്നതിനുള്ള ഈ ഓവർടൈം അലവൻസ്   സ്റ്റേറ്റ്മെന്റ്,   ഓവർടൈം അലവൻസ് അനുവദിക്കുന്നതിനായുള്ള നിലവിലെ സർക്കാർ ഉത്തരവിൽ  നിഷ്കർഷിച്ചിരിക്കുന്ന  നിബന്ധനകൾ  പാലിച്ചു തന്നെയാണ്  തയ്യാറാക്കി സമർപ്പിക്കുന്നതെന്ന് സാക്ഷ്യപ്പെടുത്തുന്നു.'

    },

    mounted: function mounted() {

        //alert(navigator.sayswho);
        var browser = navigator.sayswho.toLowerCase();

        //in accounts D XP, even FF 50 cant show mal
        if (isWinXP) {
            browser = 'firefox 10';
        }
        var x = browser.indexOf('firefox');

        if (-1 != x) {

            var ffver = browser.substr(x + 7).trim();

            if (parseInt(ffver) < 25) {

                this.approvaltext = 'This statement of overtime allowance claim, for overtime duty performed in connection with the ' + klasession_for_JS + ', is in accordance with the existing Govt order regarding granting of overtime allowance';
            }
        }
    },
    // define methods under the `methods` object
    methods: {
        forwardClick: function forwardClick(userdisplname) {

            if (userdisplname == '') {
                this.$swal('Please Enter Name', 'Kindly enter your name in the Profile page first.', 'error');
                return;
            }

            var self = this;
            this.$swal(_defineProperty({
                text: 'Forward form to:',

                input: 'select',
                type: 'question',
                confirmButtonText: '<i class="fa fa-mail-forward"></i> Forward',
                inputOptions: forwardarray,
                inputPlaceholder: Object.keys(forwardarray).length > 1 ? 'Select' : '',
                inputValue: initalvalue,
                // inputclass:'form-control',
                showCancelButton: true,
                showCloseButton: false,
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
                    timer: 700,
                    useRejections: false
                }).then(function (result) {
                    window.location.href = urlredirect;
                });
            });
        },
        submitClick: function submitClick(userdisplname) {

            if (userdisplname == '') {
                this.$swal('Please Enter Name', 'Kindly enter your name in the Profile page first.', 'error');
                return;
            }
            if (!dataentry_allowed) {
                this.$swal('Submit', 'Form submission disabled', 'error');
                return;
            }

            var self = this;
            this.$swal({
                titleText: 'Submit to Accounts:',
                text: 'Are you sure you want to submit this form to Accounts?',
                type: 'question',
                confirmButtonText: '<i class="fa fa-envelope"></i> Submit',
                allowEnterKey: false,
                showCancelButton: true,
                showCloseButton: false,
                allowOutsideClick: false,
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
                    timer: 700,
                    useRejections: false //prevent exception due to uncatched timer event
                }).then(function (result) {
                    window.location.href = urlredirect;
                });
            });
        },
        sendbackClick: function sendbackClick() {
            var self = this;
            this.$swal({
                text: 'Send back to the form creator?',
                input: 'textarea',
                type: 'question',
                inputPlaceholder: 'Enter remarks if any (max 190 chars)',
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
                    timer: 1000,
                    useRejections: false
                }).then(function (result) {
                    window.location.href = urlredirect;
                });
            });
        },
        sendonelevelbackClick: function sendonelevelbackClick() {
            var self = this;
            this.$swal({
                text: 'Send back to the previous officer?',
                input: 'textarea',
                type: 'question',
                inputPlaceholder: 'Enter remarks if any (max 190 chars)',
                inputValue: remarks,
                confirmButtonText: '<i class="fa fa-reply"></i> Send one level back',
                allowEnterKey: false,
                showCancelButton: true,
                showCloseButton: true,
                focusCancel: true,
                showLoaderOnConfirm: true,
                preConfirm: function preConfirm(text) {
                    return new Promise(function (resolve, reject) {

                        var obj = { 'remarks': text };

                        axios.put(urlformsendonelevelback + "/" + formid, obj).then(function (response) {

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
                    timer: 1000,
                    useRejections: false
                }).then(function (result) {
                    window.location.href = urlredirect;
                });
            });
        },
        ignoreClick: function ignoreClick(ignore) {
            var self = this;
            this.$swal({
                text: ignore ? 'Withhold this form?' : 'Release?',
                input: ignore ? 'textarea' : null,
                type: 'question',
                inputPlaceholder: 'Enter remarks if any (max 190 chars)',
                inputValue: remarks,

                confirmButtonText: ignore ? '<i class="fa fa-thumbs-down"></i> Withhold' : '<i class="fa fa-thumbs-up"></i> Release',

                allowEnterKey: false,
                showCancelButton: true,
                showCloseButton: true,
                focusCancel: true,
                showLoaderOnConfirm: true,
                preConfirm: function preConfirm(text) {
                    return new Promise(function (resolve, reject) {

                        var obj = { 'remarks': text };

                        axios.put(urlformignore + "/" + formid, obj).then(function (response) {

                            if (response.data.result) {
                                resolve();
                            } else {
                                reject('Error, cannot ignore');
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
                    html: ignore ? 'Withheld!' : 'Released',
                    timer: 1000,
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