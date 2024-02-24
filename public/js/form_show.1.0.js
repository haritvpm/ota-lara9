/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/assets/js/utils.js":
/*!**************************************!*\
  !*** ./resources/assets/js/utils.js ***!
  \**************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "checkDatesAndOT": () => (/* binding */ checkDatesAndOT),
/* harmony export */   "setEmployeeTypes": () => (/* binding */ setEmployeeTypes),
/* harmony export */   "stringTimeToDate": () => (/* binding */ stringTimeToDate),
/* harmony export */   "timePeriodIncludesPeriod": () => (/* binding */ timePeriodIncludesPeriod),
/* harmony export */   "toHoursAndMinutes": () => (/* binding */ toHoursAndMinutes)
/* harmony export */ });
function setEmployeeTypes(row) {
  if (!row.hasOwnProperty("designation") || !row.hasOwnProperty("category") || !row.hasOwnProperty("normal_office_hours")) {
    console.error("setEmployeeTypes - not all Property set");
  }
  console.log("setEmployeeTypes");
  row.isPartime = row.designation.toLowerCase().indexOf("part time") != -1 || row.category.toLowerCase().indexOf("parttime") != -1 || row.designation.toLowerCase().indexOf("parttime") != -1 || row.normal_office_hours == 3; //ugly
  row.isFulltime = row.category.toLowerCase().indexOf("fulltime") != -1 || row.normal_office_hours == 6;
  row.isWatchnward = row.category.toLowerCase().indexOf("watch") != -1;
  row.isNormal = !row.isPartime && !row.isFulltime && !row.isWatchnward;
}
function stringTimeToDate(sTimeWithSemicolonSeperator) {
  var time = sTimeWithSemicolonSeperator.split(":").map(Number);
  //warning: months in JS starts from 0
  return Date.UTC(2000, 1, 1, time[0], time[1]);
}
;
function timePeriodIncludesPeriod(from, to, fromReq, toReq) {
  var datefrom = stringTimeToDate(from);
  var dateto = stringTimeToDate(to);
  var time800am = stringTimeToDate(fromReq);
  var time530pm = stringTimeToDate(toReq);
  return time800am >= datefrom && time530pm <= dateto;
}
function checkDatesAndOT(row, data) {
  //we need to give some leeway. so commenting
  var count = 0;
  var total_ot_days = 0;
  for (var i = 0; i < data.dates.length; i++) {
    // console.log(data.dates[i])

    var punchin = data.dates[i].punchin;
    var punchout = data.dates[i].punchout;
    if ("N/A" == punchin) {
      //no punching day. NIC server down
      data.dates[i].ot = 'Enter in OT Form';
      continue;
    }
    total_ot_days++;
    if (!punchin || !punchout) {
      //not punched
      data.dates[i].ot = punchin || punchout ? 'Not Punched?' : 'Leave?';
      continue;
    }
    data.dates[i].ot = 'NO';
    if (row.isPartime) {
      console.log('p');
      if (timePeriodIncludesPeriod(punchin, punchout, "06:05", "11:25")) {
        data.dates[i].ot = 'YES';
        count++;
      } else {
        data.dates[i].ot = 'No. (06:00 - 11:30)';
      }
    } else if (row.isFulltime) {
      if (timePeriodIncludesPeriod(punchin, punchout, "06:05", "16:25")) {
        count++;
        data.dates[i].ot = 'YES';
      } else {
        data.dates[i].ot = 'No. (06:00 - 4:30pm)';
      }
    } else if (row.isWatchnward) {
      //no punching
    } //all other employees for sitting days
    else {
      console.log('n');
      if (timePeriodIncludesPeriod(punchin, punchout, "08:05", "17:25")) {
        count++;
        data.dates[i].ot = 'YES';
      } else {
        data.dates[i].ot = 'No. (08:00 - 5:30pm)';
      }
    }
  }
  return {
    count: count,
    modaldata: data.dates,
    total_ot_days: total_ot_days
  };
}
function toHoursAndMinutes(totalMinutes) {
  var hours = Math.floor(totalMinutes / 60);
  var minutes = totalMinutes % 60;
  if (hours) return "".concat(hours, ":").concat(padToTwoDigits(minutes), " hour");
  return "".concat(minutes, " min");
}
function padToTwoDigits(num) {
  return num.toString().padStart(2, '0');
}

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
/*!**********************************************!*\
  !*** ./resources/assets/js/form_show.1.0.js ***!
  \**********************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./utils.js */ "./resources/assets/js/utils.js");
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

// register modal component
Vue.component("modal", {
  template: "#my-modal"
});
var vm = new Vue({
  el: '#app',
  data: {
    agreechecked: false,
    needspostingchecked: false,
    approvaltext: malkla + ' കേരള നിയമസഭയുടെ  ' + sessionnumber + '-ാം സമ്മേളനത്തോടനുബന്ധിച്ച് അധികജോലിക്കു നിയോഗിക്കപ്പെട്ട ജീവനക്കാർക്ക്  ഓവർടൈം അലവൻസ് അനുവദിക്കുന്നതിനുള്ള ഈ ഓവർടൈം അലവൻസ്   സ്റ്റേറ്റ്മെന്റ്,   ഓവർടൈം അലവൻസ് അനുവദിക്കുന്നതിനായുള്ള നിലവിലെ സർക്കാർ ഉത്തരവിൽ  നിഷ്കർഷിച്ചിരിക്കുന്ന  നിബന്ധനകൾ  പാലിച്ചു തന്നെയാണ്  തയ്യാറാക്കി സമർപ്പിക്കുന്നതെന്ന് സാക്ഷ്യപ്പെടുത്തുന്നു.',
    approvalpostingcheckedtext: 'നിയമസഭാ സെക്രട്ടറിയുടെ മുൻ‌കൂട്ടിയുള്ള അനുമതിയോടെയാണ് ഈ ഓവർടൈമിന് ജീവനക്കാരെ നിയോഗിച്ചതെന്ന് സാക്ഷ്യപ്പെടുത്തുന്നു.',
    modaldata: [],
    modaldata_totalOT: 0,
    modaldata_totalOTDays: 0,
    modaldata_empl: ""
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
    showSittingOTs: function showSittingOTs(row) {
      var _this = this;
      row = JSON.parse(row);
      // console.log(row);
      // console.log(row.pen);
      // console.log(row.from);

      axios.get("".concat(urlajaxgetpunchsittings, "/").concat(session, "/").concat(row.from, "/").concat(row.to, "/").concat(row.pen, "/").concat(row.aadhaarid)).then(function (response) {
        //  console.log(response); 
        if (response.data) {
          //todo ask if unpresent dates where present
          (0,_utils_js__WEBPACK_IMPORTED_MODULE_0__.setEmployeeTypes)(row);
          var _checkDatesAndOT = (0,_utils_js__WEBPACK_IMPORTED_MODULE_0__.checkDatesAndOT)(row, response.data),
            count = _checkDatesAndOT.count,
            modaldata = _checkDatesAndOT.modaldata,
            total_ot_days = _checkDatesAndOT.total_ot_days;
          _this.modaldata = modaldata;
          _this.modaldata_totalOT = count;
          _this.modaldata_empl = row.pen;
          _this.modaldata_totalOTDays = total_ot_days;
          document.getElementById('modalOpenBtn').click();
        }
      })["catch"](function (err) {});
    },
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
})();

/******/ })()
;