/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!************************************************!*\
  !*** ./resources/assets/js/form_other_show.js ***!
  \************************************************/
function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function _defineProperty(obj, key, value) { key = _toPropertyKey(key); if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }
Vue.use(VueSweetAlert["default"]);
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
          timer: 1500,
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