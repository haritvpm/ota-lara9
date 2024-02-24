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
      //  data.dates[i].ot = 'Enter in OT Form'
      data.dates[i].otna = true;
      continue;
    }
    data.dates[i].otna = false;
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
  !*** ./resources/assets/js/form_sitting2.js ***!
  \**********************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./utils.js */ "./resources/assets/js/utils.js");


var _methods;
function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function _defineProperty(obj, key, value) { key = _toPropertyKey(key); if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }

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
    modaldata_totalOT: 0,
    modaldata_empl: "",
    modaldata_totalOTDays: 0
  },
  created: function created() {
    Vue.set(this.$data, 'form', _form);
    //copy name to PEN field

    /*
      for(var i=0; i < this.form.overtimes.length; i++){
         
         //copy if we have a name
         if(this.form.overtimes[i].name != null){
           this.form.overtimes[i].pen += '-' +this.form.overtimes[i].name ;
       }
          
      }*/

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
    isActive: function isActive() {}
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
        punching: true //by default everyone ha punching
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
      self.modaldata = [];
      self.modaldata_totalOT = 0;
      self.modaldata_empl = row.pen;
      axios.get("".concat(urlajaxgetpunchsittings, "/").concat(self.form.session, "/").concat(row.from, "/").concat(row.to, "/").concat(row.pen, "/").concat(row.aadhaarid)).then(function (response) {
        //console.log(response);
        if (response.data) {
          //todo ask if unpresent dates where present
          (0,_utils_js__WEBPACK_IMPORTED_MODULE_0__.setEmployeeTypes)(row);
          //warning this func modifies response.data
          var _checkDatesAndOT = (0,_utils_js__WEBPACK_IMPORTED_MODULE_0__.checkDatesAndOT)(row, response.data),
            count = _checkDatesAndOT.count,
            modaldata = _checkDatesAndOT.modaldata,
            total_ot_days = _checkDatesAndOT.total_ot_days;
          if (row.count != count) {
            row.count = count;

            //vue does not update time if we change date as it does not watch for array changes
            //https://v2.vuejs.org/v2/guide/reactivity#Change-Detection-Caveats
            Vue.set(self.form.overtimes, index, row);
          }
          if (show) {
            self.modaldata_totalOT = count;
            self.modaldata = modaldata;
            self.modaldata_totalOTDays = total_ot_days;
            // this.showModal = true
            document.getElementById('modalOpenBtn').click();
          }
        }
      })["catch"](function (err) {
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