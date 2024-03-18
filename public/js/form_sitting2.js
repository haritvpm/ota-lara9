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
/* harmony export */   "FT_06_05": () => (/* binding */ FT_06_05),
/* harmony export */   "FT_16_25": () => (/* binding */ FT_16_25),
/* harmony export */   "FT_HOSTEL_07_05": () => (/* binding */ FT_HOSTEL_07_05),
/* harmony export */   "FT_HOSTEL_17_25": () => (/* binding */ FT_HOSTEL_17_25),
/* harmony export */   "PT_06_05": () => (/* binding */ PT_06_05),
/* harmony export */   "PT_11_25": () => (/* binding */ PT_11_25),
/* harmony export */   "PT_HOSTEL_07_05": () => (/* binding */ PT_HOSTEL_07_05),
/* harmony export */   "PT_HOSTEL_12_25": () => (/* binding */ PT_HOSTEL_12_25),
/* harmony export */   "REG_08_05": () => (/* binding */ REG_08_05),
/* harmony export */   "REG_17_25": () => (/* binding */ REG_17_25),
/* harmony export */   "checkDatesAndOT": () => (/* binding */ checkDatesAndOT),
/* harmony export */   "setEmployeeTypes": () => (/* binding */ setEmployeeTypes),
/* harmony export */   "sittingAllowableForNonAebasDay": () => (/* binding */ sittingAllowableForNonAebasDay),
/* harmony export */   "stringTimeToDate": () => (/* binding */ stringTimeToDate),
/* harmony export */   "timePeriodIncludesPeriod": () => (/* binding */ timePeriodIncludesPeriod),
/* harmony export */   "toHoursAndMinutes": () => (/* binding */ toHoursAndMinutes)
/* harmony export */ });
var REG_08_05 = "08:05";
var REG_17_25 = "17:25";
var PT_06_05 = "06:05";
var PT_11_25 = "11:25";
var PT_HOSTEL_07_05 = "07:05";
var PT_HOSTEL_12_25 = "12:25";
var FT_06_05 = "06:05";
var FT_16_25 = "16:15";
var FT_HOSTEL_07_05 = "07:05";
var FT_HOSTEL_17_25 = "17:15";
function setEmployeeTypes(row) {
  if (!row.hasOwnProperty("designation") || !row.hasOwnProperty("category") || !row.hasOwnProperty("normal_office_hours")) {
    console.error("setEmployeeTypes - not all Property set");
  }
  // console.log("setEmployeeTypes");
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
//check if punchin or out if available, fails
//if time is not available, it is ok
function sittingAllowableForNonAebasDay(from, to, fromReq, toReq) {
  if (from) {
    var datefrom = stringTimeToDate(from);
    var time800am = stringTimeToDate(fromReq);
    if (datefrom > time800am) return false;
  }
  if (to) {
    var dateto = stringTimeToDate(to);
    var time530pm = stringTimeToDate(toReq);
    if (dateto < time530pm) return false;
  }
  return true;
}
function checkDatesAndOT(row, data) {
  //we need to give some leeway. so commenting
  var count = 0; //ot given to user
  var total_nondecision_days = 0;
  var total_userdecision_days = 0;
  for (var i = 0; i < data.dates.length; i++) {
    // console.log(data.dates[i])

    var punchin = data.dates[i].punchin;
    var punchout = data.dates[i].punchout;
    //if user has made all yes/no decisions, row.overtimesittings will not be null. it can be [] or [<dates>]
    var pos = row.overtimesittings ? row.overtimesittings.indexOf(data.dates[i].date) : -2;
    if (punchin && punchout) {
      //punched

      if (row.isPartime) {
        if (timePeriodIncludesPeriod(punchin, punchout, PT_06_05, PT_11_25) || timePeriodIncludesPeriod(punchin, punchout, PT_HOSTEL_07_05, PT_HOSTEL_12_25)) {
          data.dates[i].ot = 'YES';
          count++;
        } else {
          data.dates[i].ot = 'No. (6/7 am - 11:30/12:30)';
        }
      } else if (row.isFulltime) {
        if (timePeriodIncludesPeriod(punchin, punchout, FT_06_05, FT_16_25) || timePeriodIncludesPeriod(punchin, punchout, FT_HOSTEL_07_05, FT_HOSTEL_17_25)) {
          count++;
          data.dates[i].ot = 'YES';
        } else {
          data.dates[i].ot = 'No. (6/7 am - 4:30pm/5:30pm)';
        }
      } else if (row.isWatchnward) {
        //no punching
      } //all other employees for sitting days
      else {
        if (timePeriodIncludesPeriod(punchin, punchout, REG_08_05, REG_17_25)) {
          count++;
          data.dates[i].ot = 'YES';
        } else {
          data.dates[i].ot = 'No. (08:00 - 5:30pm)';
        }
      }
      data.dates[i].userdecision = false;
      total_nondecision_days++;
      if (data.dates[i].ot != 'YES' && pos >= 0) row.overtimesittings.splice(pos, 1); //remove from sel if it is NO
      continue;
    }

    //punchin or out is not available
    if (data.dates[i].aebasday) {
      data.dates[i].userdecision = false;
      data.dates[i].ot = punchin || punchout ? 'Not Punched?' : 'Leave?';
      total_nondecision_days++;
      if (pos >= 0) row.overtimesittings.splice(pos, 1); //remove from sel if it is NO
      continue;
    }

    //non aebasday, check if user has not punched incorrectly when server was failing
    data.dates[i].userdecision = false;
    if (row.isPartime) {
      if (sittingAllowableForNonAebasDay(punchin, punchout, PT_06_05, PT_11_25) || sittingAllowableForNonAebasDay(punchin, punchout, PT_HOSTEL_07_05, PT_HOSTEL_12_25)) {
        data.dates[i].userdecision = true;
      } else {
        data.dates[i].ot = 'No. (6/7 - 11:30/12:30)';
      }
    } else if (row.isFulltime) {
      if (sittingAllowableForNonAebasDay(punchin, punchout, FT_06_05, FT_16_25) || sittingAllowableForNonAebasDay(punchin, punchout, FT_HOSTEL_07_05, FT_HOSTEL_17_25)) {
        data.dates[i].userdecision = true;
      } else {
        data.dates[i].ot = 'No. (6/7 - 4:30pm/5:30pm)';
      }
    } else if (row.isWatchnward) {
      //no punching
    } //all other employees for sitting days
    else {
      if (sittingAllowableForNonAebasDay(punchin, punchout, REG_08_05, REG_17_25)) {
        data.dates[i].userdecision = true;
      } else {
        data.dates[i].ot = 'No. (08:00 - 5:30pm)';
      }
    }
    if (data.dates[i].userdecision) {
      data.dates[i].ot = pos == -2 ? '*' : 'NO'; //-2 if user not dtermined
      total_userdecision_days++;
      if (pos >= 0) {
        data.dates[i].ot = 'YES';
        count++;
      }
    } else {
      total_nondecision_days++;
      if (!data.dates[i].userdecision && pos >= 0) row.overtimesittings.splice(pos, 1); //remove from sel if it is NO
    }
  }

  return {
    count: count,
    modaldata: data.dates,
    total_nondecision_days: total_nondecision_days,
    total_userdecision_days: total_userdecision_days
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
  !*** ./resources/assets/js/form_sitting2.js ***!
  \**********************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./utils.js */ "./resources/assets/js/utils.js");


function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
var _methods;
function _defineProperty(obj, key, value) { key = _toPropertyKey(key); if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }
function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }
function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }
function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter); }
function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }
function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

var vm = new Vue({
  el: '#app',
  data: {
    isProcessing: false,
    form: {},
    errors: {},
    myerrors: [],
    // muloptions: designations,
    pen_names: [],
    pen_names_to_desig: [],
    presets: presets,
    calenderdays2: calenderdays2,
    showModal: false,
    modaldata: [],
    modaldata_fixedOT: 0,
    modaldata_row: null,
    modaldata_totalOTDays: 0,
    //modaldata_seldays:[],
    modaldata_showonly: false
  },
  created: function created() {
    for (var i = 0; i < _form.overtimes.length; i++) {
      //console.log( _form.overtimes[i].overtimesittings)
      //console.log( _form.overtimes[i].overtimesittings_)row.overtimesittings.map(s => s.date);
      _form.overtimes[i].overtimesittings = _.uniq(_form.overtimes[i].overtimesittings.map(function (s) {
        return s.date;
      }));
      //_form.overtimes[i].overtimesittings =  _.uniq(_form.overtimes[i].overtimesittings_);
    }

    Vue.set(this.$data, 'form', _form);
    //copy name to PEN field
    $('[data-widget="pushmenu"]').PushMenu('collapse');
    this.sessionchanged();
    if (this.form.session != '' && this.form.overtimes.length == 0) {//sessions available for dataentry,
      //and this is a new form, not editing existing
      // this.addRow();
    }
  },
  mounted: function mounted() {},
  computed: {
    configdate: function configdate() {
      var self = this;
      return {
        //dateFormat: 'd-m-Y',
        //enable: calenderdays2[self.form.session]  
        format: 'DD-MM-YYYY',
        useCurrent: false,
        // useStrict : true,
        //inline: true,
        enabledDates: Object.keys(calenderdaysmap).map(function (x) {
          return moment(x, "DD-MM-YYYY").format('YYYY-MM-DD');
        })
      };
    },
    isActive: function isActive() {},
    yesModalDays: function yesModalDays() {
      return this.modaldata.filter(function (x) {
        return x.ot == 'YES';
      }).map(function (x) {
        return x.date;
      });
    },
    yesAndNodaysModalDays: function yesAndNodaysModalDays() {
      return this.modaldata.filter(function (x) {
        return x.ot == 'YES' || x.ot == 'NO' || x.userdecision == false;
      }).map(function (x) {
        return x.date;
      });
    }
  },
  watch: {},
  methods: (_methods = {
    sessionchanged: function sessionchanged() {
      this.myerrors = [];
      //this.configdate.enable =  calenderdays2[this.form.session]
      if (calenderdays2[this.form.session] != undefined) {
        this.form.date_from = calenderdays2[this.form.session][0];
        this.form.date_to = calenderdays2[this.form.session][calenderdays2[this.form.session].length - 1];
      } else {
        this.form.date_from = '';
        this.form.date_to = '';
      }
    },
    onChange: function onChange() {
      this.myerrors = [];
      //this.slotoptions = this.slotoptions
      // this.selectdaylabel =  ': ' + calenderdaysmap [this.form.duty_date]
      //this.form.overtime_slot =''
    },

    onRowPeriodChange: function onRowPeriodChange(index) {
      //if( e?.type != 'dp' ) return ; //this func seems to be called twice on date change. this prevents that as the first call does not have that set
      //	console.log(i)
      //reset count to zero
      this.form.overtimes[index].count = "";
      this.form.overtimes[index].overtimesittings = null;
      this.getSittingOTs(index);
    },
    addRow: function addRow() {
      //  var elem = document.createElement('tr');
      var self = this;
      if (!this.rowsvalid()) {
        return;
      }

      //var prevrow = self.form.overtimes.length > 0 ? self.form.overtimes[self.form.overtimes.length - 1] : null;
      //do the changes in preset loading too
      self.form.overtimes.push({
        pen: "",
        designation: "",
        from: self.form.date_from,
        to: self.form.date_to,
        count: "",
        worknature: "",
        slots: [],
        aadhaarid: null,
        punching: true,
        //by default everyone ha punching
        isProcessing: false,
        overtimesittings: null //days user has worked. important to set null which means user has not selected yes/no for manualentry days
      });

      this.pen_names = []; //clear previos selection from dropdown
      this.pen_names_to_desig = [];
      this.$nextTick(function () {
        self.$refs["field-" + (self.form.overtimes.length - 1)][0].$el.focus();
      });
    },
    removeElement: function removeElement(index) {
      if (this.form.overtimes[index].pen == '' || confirm("Remove this row?")) {
        //this.myerrors = [];
        this.form.overtimes.splice(index, 1);
        this.myerrors = [];
        this.errors = {};
      }
    },
    limitText: function limitText(count) {
      return "and ".concat(count, " more");
    },
    asyncFind: _.debounce(function (query) {
      //  this.isLoading = true
      // Make a request for a user with a given ID
      this.mydelayedsearch(query);
      this.myerrors = [];
    }, 500),
    mydelayedsearch: function mydelayedsearch(query) {
      var self = this;
      if (query.length >= 3) {
        //axios.headers.common['X-CSRF-TOKEN'] = '{{csrf_token()}}';
        //axios.get('/overtime-allowance/public/admin/employees/ajaxfind/'+ query).then(response => {
        axios.get(urlajaxpen + '/' + query).then(function (response) {
          // console.log(response.data);
          self.pen_names = response.data.pen_names;
          self.pen_names_to_desig = response.data.pen_names_to_desig;
          //this.isLoading = false
        })["catch"](function (response) {
          // alert (JSON.stringify(response.data))    // alerts {"myProp":"Hello"};
        });
      }
    },
    modalClosed: function modalClosed() {
      // console.log(this.modaldata_seldays)  

      var yesdays = this.modaldata.filter(function (x) {
        return x.ot == 'YES' && x.userdecision == false;
      }).map(function (x) {
        return x.date;
      });
      var userseldays = this.modaldata.filter(function (x) {
        return x.ot == 'YES' && x.userdecision == true;
      }).map(function (x) {
        return x.date;
      });
      this.modaldata_row.overtimesittings = _toConsumableArray(new Set([].concat(_toConsumableArray(yesdays), _toConsumableArray(userseldays))));
      this.modaldata_row.count = this.modaldata_row.overtimesittings.length;
      // console.log(this.modaldata_row.overtimesittings)  

      //copy dates from 

      //vue does not update time if we change date as it does not watch for array changes
      //https://v2.vuejs.org/v2/guide/reactivity#Change-Detection-Caveats
      //	Vue.set(this.form.overtimes,index, row)
    },

    showSittingOTs: function showSittingOTs(index) {
      this.getSittingOTs(index, true);
    },
    getSittingOTs: function getSittingOTs(index) {
      var _this = this;
      var show = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
      var self = this;
      var row = self.form.overtimes[index];
      if (row.pen == "" || !self.form.session || !row.from || !row.to) {
        // console.log(self.form.session | row.from | row.to)  
        return;
      }
      ;
      console.log(row.overtimesittings);
      self.modaldata = [];
      self.modaldata_fixedOT = 0;
      self.modaldata_row = row;
      row.isProcessing = true;
      axios.get("".concat(urlajaxgetpunchsittings, "/").concat(self.form.session, "/").concat(row.from, "/").concat(row.to, "/").concat(row.pen, "/").concat(row.aadhaarid)).then(function (response) {
        row.isProcessing = false;
        if (response.data) {
          //todo ask if unpresent dates where present
          (0,_utils_js__WEBPACK_IMPORTED_MODULE_0__.setEmployeeTypes)(row);
          //warning this func modifies response.data
          var _checkDatesAndOT = (0,_utils_js__WEBPACK_IMPORTED_MODULE_0__.checkDatesAndOT)(row, response.data),
            count = _checkDatesAndOT.count,
            modaldata = _checkDatesAndOT.modaldata,
            total_nondecision_days = _checkDatesAndOT.total_nondecision_days,
            total_userdecision_days = _checkDatesAndOT.total_userdecision_days;
          //date period may have changed. only include those dates and remove the rest
          //this is to copy the user decided dates to new array.
          //overtimesittings_ has the original data from db
          //let temp =  this.modaldata.filter( x => row.overtimesittings_.indexOf( x.date ) != -1 )
          //row.overtimesittings = [...modaldata.map( x => x.date )]
          if (row.count != count && total_userdecision_days == 0) {
            //if there are no days that are either MANUALENTRy or NOPUNCHING
            row.count = count;
            //vue does not update time if we change date as it does not watch for array changes
            //https://v2.vuejs.org/v2/guide/reactivity#Change-Detection-Caveats
          }

          Vue.set(self.form.overtimes, index, row); //update isProcessing

          if (show) {
            self.modaldata_fixedOT = count;
            self.modaldata = modaldata;
            self.modaldata_totalOTDays = total_nondecision_days + total_userdecision_days;
            //let yesdays = modaldata.filter( x => x.ot == 'YES' && x.userdecision == false ).map( x => x.date )
            //overtimesittings stores prev selected days/ find only those days from the period
            //if user changes perod without userdecision and then backagain, this will be lost.
            //but if user sets and then opens again, we need this
            //let temp =  row.overtimesittings.filter( date => modaldata.map(d=>d.date).indexOf( date ) != -1 )
            // self.modaldata_seldays =  [ ...new Set([...yesdays,...temp])]
            document.getElementById('modalOpenBtn').click();
          }
        }
      })["catch"](function (err) {
        row.isProcessing = false;
        row.count = 0;
        Vue.set(_this.form.overtimes, index, row);
      });
    },
    clearAll: function clearAll() {
      this.pen_names = [];
      this.pen_names_to_desig = [];
    }
  }, _defineProperty(_methods, "limitText", function limitText(count) {
    return "and ".concat(count, " other countries");
  }), _defineProperty(_methods, "changeSelect", function changeSelect(selectedOption, id) {
    this.myerrors = [];
    var self = this;
    var desig = self.pen_names_to_desig[selectedOption];
    self.$nextTick(function () {
      var row = self.form.overtimes[id];
      row.category = "";
      row.normal_office_hours = 0;
      row.employee_id = null;

      //added no change if a desig already exists
      //to prevent an issue where designation is changeed was wrong
      //try with vince - vincent prasad and dr vincent
      if (desig !== undefined) {
        row.designation = desig.desig;
        row.aadhaarid = desig.aadhaarid;
        row.punching = desig.punching;
        row.normal_office_hours = desig.desig_normal_office_hours;
        row.category = desig.category;
        row.employee_id = desig.employee_id;
        row.isProcessing = false;
        row.count = "";
        row.overtimesittings = null;
        (0,_utils_js__WEBPACK_IMPORTED_MODULE_0__.setEmployeeTypes)(row);
        self.getSittingOTs(id);
      }
    });
  }), _defineProperty(_methods, "checkDuplicates", function checkDuplicates() {
    var self = this;
    //see if there are duplicates
    var obj = {};
    for (var i = 0; i < self.form.overtimes.length; i++) {
      if (obj[self.form.overtimes[i].pen] == undefined) {
        obj[self.form.overtimes[i].pen] = true;
      } else {
        this.myerrors.push('Duplicate name found: ' + self.form.overtimes[i].pen);
        return false;
      }
    }
    return true;
  }), _defineProperty(_methods, "rowsvalid", function rowsvalid() {
    this.myerrors = [];
    var self = this;
    if (self.form.session == '' || self.form.date_from == '' || self.form.date_to == '') {
      //this.myerrors.push( 'Please select session/dates' )
      this.$swal('Error', "Please select session/dates!", 'error');
      return false;
    }
    if (calenderdays2[self.form.session] == undefined) {
      this.$swal('Error', 'Session calender not valid', 'error');
      return false;
    }
    if (-1 == calenderdays2[self.form.session].indexOf(self.form.date_from) || -1 == calenderdays2[self.form.session].indexOf(self.form.date_to)) {
      this.$swal('Error', 'Please select a valid from-date/to-date for the selected session', 'error');
      return false;
    }

    //check if date from less than date to

    {
      //date.parse returns number of milliseconds elapsed since 1970
      var date1 = self.form.date_from.split("-").map(Number);
      var date2 = self.form.date_to.split("-").map(Number);

      //warning: months in JS starts from 0
      var datefrom = new Date(date1[2], date1[1] - 1, date1[0]);
      var dateto = new Date(date2[2], date2[1] - 1, date2[0]);
      if (datefrom > dateto) {
        //it can be equial though
        //this.myerrors.push( 'Date-from cannot be greater than Date-to')
        this.$swal('Error', "Date-from cannot be greater than Date-to!", 'error');
        return false;
      }
    }
    for (var i = 0; i < self.form.overtimes.length; i++) {
      var row = self.form.overtimes[i];
      if (row.pen == '' || row.designation == '' || row.from == '' || row.to == '' || row.from == null || row.to == null || +row.count <= 0 || isNaN(+row.count)) {
        //this.myerrors.push("Fill all the required fields!");
        this.$swal('Row: ' + (i + 1), "Fill all the required fields in each row!", 'error');
        return false;
      }
    }
    var totalsittings = calenderdays2[this.form.session].length;
    if (self.form.overtimes.some(function (row) {
      return +row.count > totalsittings;
    })) {
      this.myerrors.push("Total sitting days cannot be more than " + totalsittings);
      return false;
    }

    //check time diff
    for (var i = 0; i < self.form.overtimes.length; i++) {
      //date.parse returns number of milliseconds elapsed since 1970
      var date1 = self.form.overtimes[i].from.split("-").map(Number);
      var date2 = self.form.overtimes[i].to.split("-").map(Number);

      //warning: months in JS starts from 0
      var datefrom = new Date(date1[2], date1[1] - 1, date1[0]);
      var dateto = new Date(date2[2], date2[1] - 1, date2[0]);
      if (datefrom > dateto) {
        this.myerrors.push('Row ' + (i + 1) + ': Period-from cannot be greater than period-to');
        return false;
      }
    }
    return this.checkDuplicates();
  }), _defineProperty(_methods, "create", function create() {
    if (this.isProcessing) {
      return;
    }
    this.isProcessing = true;
    if (!this.rowsvalid()) {
      this.isProcessing = false;
      return;
    }
    var self = this;
    if (self.form.overtimes.length <= 0) {
      //this.myerrors.push("Fill all the required fields!");
      this.$swal('Error', "Need at least one row!", 'error');
      this.isProcessing = false;
      return false;
    }
    axios.post(urlformsubmit, self.form).then(function (response) {
      //self.$swal.close();
      // alert('success ajax');
      if (response.data.created) {
        window.location.href = urlformsucessredirect + "/" + response.data.id;
      } else {
        self.isProcessing = false;
      }
    })["catch"](function (error) {
      //console.log( error.response );
      //self.$swal.close();
      //self.$swal('Error', "!", 'error')
      self.$swal({
        type: 'error',
        title: 'Error',
        text: 'Please read the error(s) shown in red'
        // timer: 2500,
      });

      var response = error.response;
      self.isProcessing = false;
      //alert(JSON.stringify(response.data));    // alerts {"myProp":"Hello"};
      // Vue.set(self.$data, 'errors', response.data);
      self.errors = response.data;
    });
  }), _defineProperty(_methods, "update", function update() {
    if (this.isProcessing) {
      return;
    }
    this.isProcessing = true;
    if (!this.rowsvalid()) {
      this.isProcessing = false;
      return;
    }
    var self = this;
    if (self.form.overtimes.length <= 0) {
      //this.myerrors.push("Fill all the required fields!");
      this.$swal('Error', "Need at least one row!", 'error');
      this.isProcessing = false;
      return false;
    }

    //this.$swal('Please wait')
    //this.$swal.showLoading()

    var updateurl = urlformsubmit + '/' + self.form.id;
    axios.put(updateurl, self.form).then(function (response) {
      //self.$swal.close();
      // alert('success ajax');
      if (response.data.created) {
        window.location.href = urlformsucessredirect + "/" + response.data.id;
        ;
      } else {
        self.isProcessing = false;
      }
    })["catch"](function (error) {
      //console.log( error.response );
      self.$swal({
        type: 'error',
        title: 'Error',
        text: 'Please read the error(s) shown in red'
        // timer: 2500,
      });
      //self.$swal.close();
      var response = error.response;
      self.isProcessing = false;
      // alert (JSON.stringify(response.data));    // alerts {"myProp":"Hello"};
      // Vue.set(self.$data, 'errors', response.data);
      self.errors = response.data;
    });
  }), _methods)
});
})();

/******/ })()
;