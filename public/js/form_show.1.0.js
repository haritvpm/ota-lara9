/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!**********************************************!*\
  !*** ./resources/assets/js/form_show.1.0.js ***!
  \**********************************************/
function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function _defineProperty(obj, key, value) { key = _toPropertyKey(key); if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }
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
Vue.use(VueSweetAlert["default"]);
var vm = new Vue({
  el: '#app',
  data: {
    agreechecked: false,
    needspostingchecked: false,
    approvaltext: malkla + ' ???????????? ??????????????????????????????  ' + sessionnumber + '-?????? ????????????????????????????????????????????????????????????????????? ???????????????????????????????????? ???????????????????????????????????????????????? ???????????????????????????????????????  ?????????????????? ?????????????????? ???????????????????????????????????????????????????????????? ??? ?????????????????? ??????????????????   ????????????????????????????????????????????????,   ?????????????????? ?????????????????? ?????????????????????????????????????????????????????????????????? ????????????????????? ????????????????????? ????????????????????????  ???????????????????????????????????????????????????????????????  ???????????????????????????  ???????????????????????? ???????????????????????????  ????????????????????????????????? ???????????????????????????????????????????????????????????? ???????????????????????????????????????????????????????????????.',
    approvalpostingcheckedtext: '????????????????????? ?????????????????????????????????????????? ????????????????????????????????????????????? ?????????????????????????????????????????? ??? ??????????????????????????? ?????????????????????????????? ????????????????????????????????????????????? ???????????????????????????????????????????????????????????????.'
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
            var obj = {
              'owner': value
            };
            axios.put(urlformforward + "/" + formid, obj).then(function (response) {
              if (response.data.result) {
                resolve();
              } else {
                reject('Error, cannot forward');
              }
            })["catch"](function (error) {
              console.log(error.response);
              reject(error.response.data);
            });
          });
        }
      }, "allowOutsideClick", false)).then(function (result) {
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
            var obj = {
              'owner': 'admin'
            };
            axios.put(urlformsubmittoaccounts + "/" + formid, obj).then(function (response) {
              if (response.data.result) {
                resolve();
              } else {
                reject('Error, cannot submit');
              }
            })["catch"](function (error) {
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
            var obj = {
              'remarks': text
            };
            axios.put(urlformsendback + "/" + formid, obj).then(function (response) {
              if (response.data.result) {
                resolve();
              } else {
                reject('Error, cannot send back');
              }
            })["catch"](function (error) {
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
            var obj = {
              'remarks': text
            };
            axios.put(urlformsendonelevelback + "/" + formid, obj).then(function (response) {
              if (response.data.result) {
                resolve();
              } else {
                reject('Error, cannot send back');
              }
            })["catch"](function (error) {
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
            var obj = {
              'remarks': text
            };
            axios.put(urlformignore + "/" + formid, obj).then(function (response) {
              if (response.data.result) {
                resolve();
              } else {
                reject('Error, cannot ignore');
              }
            })["catch"](function (error) {
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
/******/ })()
;