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
/* harmony export */   "addMinutes": () => (/* binding */ addMinutes),
/* harmony export */   "checkDatesAndOT": () => (/* binding */ checkDatesAndOT),
/* harmony export */   "eligibleForSitOTCheck": () => (/* binding */ eligibleForSitOTCheck),
/* harmony export */   "setEmployeeTypes": () => (/* binding */ setEmployeeTypes),
/* harmony export */   "sittingAllowableForNonAebasDay": () => (/* binding */ sittingAllowableForNonAebasDay),
/* harmony export */   "stringTimeToDate": () => (/* binding */ stringTimeToDate),
/* harmony export */   "timePeriodIncludesPeriod": () => (/* binding */ timePeriodIncludesPeriod),
/* harmony export */   "toHoursAndMinutes": () => (/* binding */ toHoursAndMinutes),
/* harmony export */   "toHoursAndMinutesBare": () => (/* binding */ toHoursAndMinutesBare)
/* harmony export */ });
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
  if (!sTimeWithSemicolonSeperator) return null;
  var time = sTimeWithSemicolonSeperator.split(":").map(Number);
  //warning: months in JS starts from 0
  return Date.UTC(2000, 1, 1, time[0], time[1]);
}
;
function addMinutes(date, minutes) {
  return date + minutes * 60000;
}
function timePeriodIncludesPeriod(from, to, fromReq, toReq) {
  if (!from || !to) return false;
  var datefrom = stringTimeToDate(from);
  var dateto = stringTimeToDate(to);
  var time800am = stringTimeToDate(fromReq);
  var time530pm = stringTimeToDate(toReq);
  if (!datefrom || !dateto) return false;
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
        if (eligibleForSitOTCheck(punchin, punchout, "06:00", "11:30").eligibleForSitOT || eligibleForSitOTCheck(punchin, punchout, "07:00", "12:30").eligibleForSitOT) {
          data.dates[i].ot = 'YES';
          count++;
        } else {
          data.dates[i].ot = 'No. (6/7 am - 11:30/12:30)';
        }
      } else if (row.isFulltime) {
        console.log(punchin, punchout);
        if (eligibleForSitOTCheck(punchin, punchout, "07:00", "16:30").eligibleForSitOT || eligibleForSitOTCheck(punchin, punchout, "07:00", "17:25").eligibleForSitOT) {
          count++;
          data.dates[i].ot = 'YES';
        } else {
          data.dates[i].ot = 'No. (7 am - 4:30pm/5:30pm)';
        }
      } else if (row.isWatchnward) {
        //no punching
      } //all other employees for sitting days
      else {
        var _eligibleForSitOTChec = eligibleForSitOTCheck(punchin, punchout, "08:00", "17:30"),
          eligibleForSitOT = _eligibleForSitOTChec.eligibleForSitOT,
          graceMin = _eligibleForSitOTChec.graceMin;
        if (eligibleForSitOT) {
          count++;
          data.dates[i].ot = 'YES';
        } else {
          data.dates[i].ot = "No. ".concat(addMinutesToStringTime('08:00', graceMin), "-").concat(addMinutesToStringTime('17:30', graceMin));
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
      if (sittingAllowableForNonAebasDay(punchin, punchout, "06:10", "11:30") || sittingAllowableForNonAebasDay(punchin, punchout, "07:10", "12:30")) {
        data.dates[i].userdecision = true;
      } else {
        data.dates[i].ot = 'No. (6/7 - 11:30/12:30)';
      }
    } else if (row.isFulltime) {
      if (sittingAllowableForNonAebasDay(punchin, punchout, "07:10", "16:30") || sittingAllowableForNonAebasDay(punchin, punchout, "07:10", "17:30")) {
        data.dates[i].userdecision = true;
      } else {
        data.dates[i].ot = 'No. (7 - 4:30pm/5:30pm)';
      }
    } else if (row.isWatchnward) {
      //no punching
    } //all other employees for sitting days
    else {
      if (sittingAllowableForNonAebasDay(punchin, punchout, "08:10", "17:30")) {
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
function eligibleForSitOTCheck(punchin_str, punchout_str) {
  var req_punchin_str = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : '08:00';
  var req_punchout_str = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : '17:30';
  var grace_allowed = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : 10;
  // Convert punch-in and punch-out times to Date objects
  var punchInTime = new Date("1970-01-01T".concat(punchin_str, ":00"));
  var punchOutTime = new Date("1970-01-01T".concat(punchout_str, ":00"));

  // Define the base punch-in time (8:00 AM), maximum allowed punch-in time (8:10 AM) and base punch-out time (5:30 PM)
  var basePunchInTime = new Date("1970-01-01T".concat(req_punchin_str, ":00")); //new Date('1970-01-01T08:00:00');
  //const maxPunchInTime = new Date('1970-01-01T08:10:00');
  var maxPunchInTime = new Date(basePunchInTime.getTime() + grace_allowed * 60000);
  var basePunchOutTime = new Date("1970-01-01T".concat(req_punchout_str, ":00")); //new Date('1970-01-01T17:30:00');

  // Check if punch-in time is after 8:10 AM or punch-out time is before 5:25 PM
  if (punchInTime > maxPunchInTime || punchOutTime < basePunchOutTime) {
    return {
      eligibleForSitOT: false,
      graceMin: 0
    };
  }

  // Check if punch-in time is before or at 8:00 AM
  if (punchInTime <= basePunchInTime) {
    // In this case, punch-out time only needs to be 5:30 PM or later
    return {
      eligibleForSitOT: punchOutTime >= basePunchOutTime,
      graceMin: 0
    };
  }

  // Calculate the extra minutes after 8:00 AM
  var extraMinutes = (punchInTime - basePunchInTime) / (1000 * 60);

  // Calculate the required punch-out time
  var requiredPunchOutTime = new Date(basePunchOutTime.getTime() + extraMinutes * 60 * 1000);

  // Check if the actual punch-out time is after or equal to the required punch-out time
  return {
    eligibleForSitOT: punchOutTime >= requiredPunchOutTime,
    graceMin: extraMinutes
  };
}
function toHoursAndMinutes(totalMinutes) {
  var hours = Math.floor(totalMinutes / 60);
  var minutes = totalMinutes % 60;
  if (hours) return "".concat(hours, ":").concat(padToTwoDigits(minutes), " hour");
  return "".concat(minutes, " min");
}
function toHoursAndMinutesBare(totalMinutes) {
  var hours = Math.floor(totalMinutes / 60);
  var minutes = totalMinutes % 60;
  if (hours) return "".concat(hours, ":").concat(padToTwoDigits(minutes));
  return "0:".concat(minutes);
}
function padToTwoDigits(num) {
  return num.toString().padStart(2, '0');
}
function addMinutesToStringTime(time_str, minutes) {
  var time = time_str.split(":").map(Number);
  var totalMinutes = time[0] * 60 + time[1] + minutes;
  var hours = Math.floor(totalMinutes / 60);
  var newMinutes = totalMinutes % 60;
  return "".concat(hours, ":").concat(padToTwoDigits(newMinutes));
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
/*!**************************************!*\
  !*** ./resources/assets/js/form2.js ***!
  \**************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./utils.js */ "./resources/assets/js/utils.js");



var dateofdutyprefix = "Date of Duty";
var def_time_start = "17:30";
var def_time_end = "20:30";
function validateHhMm(textval) {
  /* var isValid = /^([0-1]?[0-9]|2[0-3]):([0-5][0-9])$/.test(textval);
    if (isValid) {
        //inputField.style.backgroundColor = '#bfa';
    } else {
        //inputField.style.backgroundColor = '#fba';
    }
     return isValid;*/

  var momentObj = moment(textval, ["HH:mm", "h:mm A"]);
  return momentObj.format("HH:mm");
}
var vm = new Vue({
  el: "#app",
  data: {
    firstOTLabel: "First",
    selectdaylabel: "",
    //dateofdutyprefix,
    isProcessing: false,
    form: {},
    errors: {},
    myerrors: [],
    // muloptions: designations,
    pen_names: [],
    pen_names_to_desig: [],
    presets: presets,
    presets_default: presets_default,
    is11thOrLater: false,
    configtime: {
      format: "HH:mm",
      stepping: 15
    }

    /* configdate: {
           dateFormat: 'd-m-Y',
          //enable: calenderdays2[this.data.form.session ]  
       }, */
  },

  created: function created() {
    Vue.set(this.$data, "form", _form);

    //copy name to PEN field
    $('[data-widget="pushmenu"]').PushMenu('collapse');
    //due to a bug, onchange is not called
    var self = this;
    this.$watch("form.duty_date", function (newVal, oldVal) {
      self.onChange();
    });
    if (autoloadpens) {
      this.loadpresetdata(autoloadpens);
    }
  },
  mounted: function mounted() {
    this.updateDateDependencies();
  },
  computed: {
    configdate: function configdate() {
      var self = this;
      return {
        //dateFormat: 'd-m-Y',
        //enable: calenderdays2[self.form.session]

        //
        format: "DD-MM-YYYY",
        useCurrent: false,
        //we have to convert the keys (dates) in calenderdaysmap to YYYY-MM-DD format
        enabledDates: Object.keys(calenderdaysmap).map(function (x) {
          return moment(x, "DD-MM-YYYY").format("YYYY-MM-DD");
        })
      };
    },
    isActive: function isActive() {},
    slotoptions: function slotoptions() {
      if (this.form.duty_date.length == 0) return '';
      //allow users to enter sitting with other OT if the NIC server was down for that day
      //the normal sitting form shows only punched days
      //we can in the future add an option for users to check days they were present in sitting form
      //for now, this is a workaround

      //let isNoPunchingDay = this.form.duty_date && (calenderdaypunching[this.form.duty_date] === 'NOPUNCHING' || calenderdaypunching[this.form.duty_date] === 'MANUALENTRY')

      if (isThirdOTAllowed) {
        switch (calenderdaysmap[this.form.duty_date]) {
          case 'Sitting day':
            return ['Second', 'Third'];
          //return isNoPunchingDay ? ['First', 'Second', 'Third'] : ['Second', 'Third'];

          case 'Prior holiday':
          case 'Holiday':
            return ['First', 'Second', 'Third', 'Additional'];
          case undefined:
            return '';
          default:
            return ['First', 'Second', 'Third'];
        }
      } else {
        switch (calenderdaysmap[this.form.duty_date]) {
          case 'Sitting day':
            return ['Second'];
          //return isNoPunchingDay ? ['First', 'Second'] : ['Second'];

          default:
            return ['First', 'Second'];
        } //switch
      }
    },

    _daylenmultiplier: function _daylenmultiplier() {
      var _daylenmultiplier$thi;
      return this.form.duty_date ? (_daylenmultiplier$thi = daylenmultiplier[this.form.duty_date]) !== null && _daylenmultiplier$thi !== void 0 ? _daylenmultiplier$thi : 1.0 : 1.0;
    },
    dayHasPunching: function dayHasPunching() {
      return calenderdaypunching[this.form.duty_date] !== 'NOPUNCHING' || calenderdaypunching[this.form.duty_date] == '';
    },
    allowPunchingEntry: function allowPunchingEntry() {
      //if date is not set, make punching true or copytonewform will have punching readonly
      var wholedayallow = this.form.duty_date ? calenderdaypunching[this.form.duty_date] === 'MANUALENTRY' : true;
      return wholedayallow;
    }
  },
  watch: {},
  methods: {
    copytimedown: function copytimedown() {
      if (this.form.overtimes.length >= 1) {
        for (var i = 0; i < this.form.overtimes.length; i++) {
          if (this.form.overtimes[i].from == "" || this.form.overtimes[i].to == "") {
            this.form.overtimes[i].from = this.form.overtimes[i].punchin;
            this.form.overtimes[i].to = this.form.overtimes[i].punchout;
          }
        }
      }
    },
    copyworknaturedown: function copyworknaturedown() {
      if (this.form.overtimes.length > 1) {
        for (var i = 1; i < this.form.overtimes.length; i++) {
          this.form.overtimes[i].worknature = this.form.overtimes[0].worknature;
        }
      }
    },
    sessionchanged: function sessionchanged() {
      //alert(this.form.session);

      alert(JSON.stringify(calenderdays2[this.form.session]));
      //this.configdate.enabledDates =  Object.keys(calenderdaysmap)
      this.myerrors = [];

      //this.form.duty_date =  '00-08-2017' //calenderdays2[this.form.session][0];

      if (this.form.duty_date != "" && this.form.duty_date != null) {
        if (-1 == calenderdays2[this.form.session].indexOf(this.form.duty_date)) {
          this.myerrors.push("For session " + this.form.session + ", please select a date between : " + calenderdays2[this.form.session][0] + " and " + calenderdays2[this.form.session][calenderdays2[this.form.session].length - 1] + ".");
        }
      }
    },
    onChange: function onChange(e) {
      //if( e?.type != 'dp' ) return ; //this func seems to be called twice on date change. this prevents that as the first call does not have that set
      this.updateDateDependencies();
      if (this.form.duty_date != "" && this.form.duty_date != null) {
        console.log('onchange2');

        //	if( e.oldDate != e.date )
        {
          this.fetchPunching();
          //clear all slots
          for (var i = 0; i < this.form.overtimes.length; i++) {
            this.form.overtimes[i].slots = [];
          }
        }

        //if dutydate is after 2024-06-01, then it is 11th or later
        var dutydate = moment(this.form.duty_date, "DD-MM-YYYY");
        this.is11thOrLater = dutydate.isAfter("2024-06-01");
        console.log('is11thOrLater: ' + this.is11thOrLater);
      }
    },
    updateDateDependencies: function updateDateDependencies() {
      if (this.form.duty_date == "" || this.form.duty_date == null) return;
      var self = this;
      this.myerrors = [];
      this.slotoptions = this.slotoptions;
      this.form.overtime_slot = "Multi";
      this.firstOTName = "";
      if (calenderdaysmap[this.form.duty_date] !== undefined) this.selectdaylabel = ": " + calenderdaysmap[this.form.duty_date];else this.selectdaylabel = ": Not valid for the session";
      this.firstOTLabel = this.selectdaylabel.indexOf("Sitting") !== -1 ? 'Sit' : "1<sup>st</sup>";
      if (-1 == calenderdays2[this.form.session].indexOf(this.form.duty_date)) {
        this.myerrors.push("For session " + this.form.session + ", please select a date between : " + calenderdays2[this.form.session][0] + " and " + calenderdays2[this.form.session][calenderdays2[this.form.session].length - 1] + ".");
      }
    },
    // onChangeSlot: function () {
    // 	this.myerrors = [];

    // 	if (this.form.overtimes.length > 0 && this.form.overtimes[0].from == "") {
    // 		//do this only if first row of a slot is empty

    // 		//clear all times
    // 		for (var i = 0; i < this.form.overtimes.length; i++) {
    // 			this.form.overtimes[i].from = "";
    // 			this.form.overtimes[i].to = "";
    // 		}
    // 	}
    // },

    fetchPunching: function fetchPunching() {
      console.log('fetchPunching');
      for (var i = 0; i < this.form.overtimes.length; i++) {
        this.fetchPunchingTimeForRow(i);
      }
    },
    addRow: function addRow() {
      this.insertElement(this.form.overtimes.length);
    },
    insertElement: function insertElement(index) {
      var self = this;
      if (!this.rowsvalid()) {
        return;
      }
      this.myerrors = [];
      //var prevrow = index > 0 && self.form.overtimes.length >= index ? self.form.overtimes[index - 1] : null;

      this.form.overtimes.splice(index, 0, {
        pen: "",
        designation: "",
        from: /*prevrow ? prevrow.from :*/"",
        to: /*prevrow ? prevrow.to :*/"",
        // worknature: prevrow ? prevrow.worknature : presets_default['default_worknature'],
        punching: self.dayHasPunching,
        punching_id: null,
        slots: []
      });
      this.pen_names = []; //clear previos selection from dropdown
      this.pen_names_to_desig = [];
      this.$nextTick(function () {
        self.$refs["field-" + index][0].$el.focus();
      });
    },
    removeElement: function removeElement(index) {
      /*if(this.form.overtimes[index].pen == '' || 
            confirm("Remove this row?"))*/
      {
        this.form.overtimes.splice(index, 1);
        this.myerrors = [];
      }
    },
    getTimeDiff: function getTimeDiff(row) {
      if ((row === null || row === void 0 ? void 0 : row.from) == "" || (row === null || row === void 0 ? void 0 : row.to) == "") {
        return "";
      }
      if (!(row !== null && row !== void 0 && row.from) || !(row !== null && row !== void 0 && row.to)) {
        return "";
      }
      var _this$strTimesToDates = this.strTimesToDatesNormalized(row.from, row.to),
        datefrom = _this$strTimesToDates.datefrom,
        dateto = _this$strTimesToDates.dateto;
      if (!datefrom || !dateto) {
        return "";
      }
      console.log(datefrom, dateto);
      return (0,_utils_js__WEBPACK_IMPORTED_MODULE_0__.toHoursAndMinutesBare)((dateto - datefrom) / 60000);
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
      var _this = this;
      if (query.length >= 3) {
        axios.get(urlajaxpen + "/" + query).then(function (response) {
          // console.log(response.data);
          _this.pen_names = response.data.pen_names;
          _this.pen_names_to_desig = response.data.pen_names_to_desig;
          //this.isLoading = false
          //alert (JSON.stringify(this.pen_names_to_desig))
        })["catch"](function (response) {
          // alert (JSON.stringify(response.data))    // alerts {"myProp":"Hello"};
        });
      }
    },
    clearAll: function clearAll() {
      this.pen_names = [];
      this.pen_names_to_desig = [];
    },
    //here, id is the ref property of multiselect which we have set as the index.
    changeSelect: function changeSelect(selectedOption, id) {
      var _this2 = this;
      //	console.log(this.form.overtimes)
      this.myerrors = [];
      var self = this;
      var desig = self.pen_names_to_desig[selectedOption];
      self.$nextTick(function () {
        var row = self.form.overtimes[id];
        row.punching = self.dayHasPunching;
        row.normal_office_hours = 0;
        row.category = "";
        row.slots = [];
        if (desig !== undefined && desig.desig) {
          row.designation = desig.desig;
          row.punching = desig.punching;
          row.normal_office_hours = desig.desig_normal_office_hours;
          row.category = desig.category;
          row.employee_id = desig.employee_id;
          row.aadhaarid = desig.aadhaarid;
          //if you add any new prop here, check to update in EmployeesController:ajaxfind,
          //MyFormsController:preparevariablesandGotoView in two locations for edit and copytonewform since we need these variables when we try to edit this
          //and also in loadpresetdata in this file itself
          (0,_utils_js__WEBPACK_IMPORTED_MODULE_0__.setEmployeeTypes)(row);
          //	console.log(row);
          if (0 == id) {
            //const { def_time_start, def_time_end } = this.getDefaultTimes(this.form.overtime_slot, row);
            //row.from = def_time_start ?? "";	row.to = def_time_end ?? "";
            row.from = "";
            row.to = "";
          }

          //self.$forceUpdate()
        }

        _this2.fetchPunchingTimeForRow(id);
      });

      //no need we will check on form submit
      //this also seems to display a warning when we
      //a. select a duplicate, b. change to a non duplicate immediately
      //that is not needed.
      //this.checkDuplicates()

      //alert(id) unable to get id. so a hack
    },
    //this can be used to update punching times if we chage calender dates after entering/loading employees.
    fetchPunchingTimeForRow: function fetchPunchingTimeForRow(index) {
      var _this3 = this;
      var self = this;
      var row = self.form.overtimes[index];
      if (row.pen == "" || !self.form.duty_date) return;
      console.log(row);

      //set punchtime if not set and available
      //reset for example if user selects another person after selecting a person with punchtime
      row.punchin = "";
      row.punchout = "";
      row.punching_id = null;
      row.punchin_from_aebas = false;
      row.punchout_from_aebas = false;
      if (self.dayHasPunching && row.punching) {
        this.isProcessing = true;
        axios.get(urlajaxgetpunchtimes + "/" + self.form.duty_date + "/" + row.pen + "/" + row.aadhaarid).then(function (response) {
          //console.log(response);
          if (response.data && response.data.hasOwnProperty("punchin") && response.data.hasOwnProperty("punchout")) {
            //console.log("set punch data");
            row.punchin = response.data.punchin;
            row.punchout = response.data.punchout;
            row.aadhaarid = response.data.aadhaarid;
            row.punching_id = response.data.id;
            row.punchin_from_aebas = response.data.punchin_from_aebas == 1;
            row.punchout_from_aebas = response.data.punchout_from_aebas == 1;
            //vue does not update time if we change date as it does not watch for array changes
            //https://v2.vuejs.org/v2/guide/reactivity#Change-Detection-Caveats
            Vue.set(_this3.form.overtimes, index, row);
          }
          _this3.isProcessing = false;
        })["catch"](function (err) {
          _this3.isProcessing = false;
          Vue.set(_this3.form.overtimes, index, row);
        });
      }
    },
    checkDuplicates: function checkDuplicates() {
      var self = this;
      //see if there are duplicates
      var obj = {};
      for (var i = 0; i < self.form.overtimes.length; i++) {
        if (obj[self.form.overtimes[i].pen] == undefined) {
          obj[self.form.overtimes[i].pen] = true;
        } else {
          this.myerrors.push("Duplicate name found: " + self.form.overtimes[i].pen);
          return false;
        }
      }
      return true;
    },
    strTimesToDatesNormalized: function strTimesToDatesNormalized(from, to) {
      var normalize = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : true;
      var datefrom = (0,_utils_js__WEBPACK_IMPORTED_MODULE_0__.stringTimeToDate)(from);
      var dateto = (0,_utils_js__WEBPACK_IMPORTED_MODULE_0__.stringTimeToDate)(to);

      //time after 12 am ?
      if (normalize && dateto <= datefrom) {
        dateto += 24 * 3600000;
      }
      return {
        datefrom: datefrom,
        dateto: dateto
      };
    },
    checkTimeWithinPunchingTime: function checkTimeWithinPunchingTime(row) {
      if (!row.punching) return true;
      var _this$strTimesToDates2 = this.strTimesToDatesNormalized(row.from, row.to),
        datefrom = _this$strTimesToDates2.datefrom,
        dateto = _this$strTimesToDates2.dateto;
      var _this$strTimesToDates3 = this.strTimesToDatesNormalized(row.punchin, row.punchout),
        datepunchin = _this$strTimesToDates3.datefrom,
        datepunchout = _this$strTimesToDates3.dateto;
      if (datepunchin > datefrom || datepunchout < dateto) {
        return false;
      }
      return true;
    },
    checkSittingDayTimeIsAsPerGO: function checkSittingDayTimeIsAsPerGO(row, i) {
      if (row.isPartime) {
        //parttime emp

        if (this.hasFirst(row.slots)) {
          if (!(0,_utils_js__WEBPACK_IMPORTED_MODULE_0__.timePeriodIncludesPeriod)(row.from, row.to, "06:10", "11:30") && !(0,_utils_js__WEBPACK_IMPORTED_MODULE_0__.timePeriodIncludesPeriod)(row.from, row.to, "07:10", "12:30")) {
            //hostel
            this.myerrors.push("Row " + (i + 1) + ": Parttime employee - time should include 06:00/7.00 to 11:30/12.30 on a sitting day");
            return false;
          }
        } else if (this.hasSecond(row.slots)) {
          //no need to strict time. let them decide for themselves. 2 to 4.30 is actual
          if (!(0,_utils_js__WEBPACK_IMPORTED_MODULE_0__.timePeriodIncludesPeriod)(row.from, row.to, "14:00", "16:30")) {
            this.myerrors.push("Row " + (i + 1) + ": Parttime employee - time should include 14:00 to 16:30 as per G.O on a sitting day");
            return false;
          }
        } else {
          //no third OT. we check that in parent function
        }
      } else if (row.isFulltime) {
        if (this.hasFirst(row.slots)) {
          ////its acutally 4.30. no need to enforce ending time. have doubts regarding mla hostel.
          if (!(0,_utils_js__WEBPACK_IMPORTED_MODULE_0__.timePeriodIncludesPeriod)(row.from, row.to, "07:10", "16:30") && !(0,_utils_js__WEBPACK_IMPORTED_MODULE_0__.timePeriodIncludesPeriod)(row.from, row.to, "07:10", "17:30")) {
            this.myerrors.push("Row " + (i + 1) + ": Fulltime employee - time shall include 7 a.m. to 4.30/5.30 pm on a sitting day");
            return false;
          }
        }
        //no second, third OT. we check that in parent function
      } else if (row.isWatchnward) {} //all other employees for sitting days
      else {
        if (this.hasFirst(row.slots)) {
          if (!(0,_utils_js__WEBPACK_IMPORTED_MODULE_0__.timePeriodIncludesPeriod)(row.from, row.to, "08:10", "17:30")) {
            this.myerrors.push("Row " + (i + 1) + ": For sitting OT, time should include 08:00 to 17:30 as per GO");
            return false;
          }
        }
      }
      return true;
    },
    timePeriodsOverlap: function timePeriodsOverlap(datefrom, dateto, sNormalStart, sNormalEnd) {
      var time800am = (0,_utils_js__WEBPACK_IMPORTED_MODULE_0__.stringTimeToDate)(sNormalStart);
      var time530pm = (0,_utils_js__WEBPACK_IMPORTED_MODULE_0__.stringTimeToDate)(sNormalEnd);
      var isoverlap = time800am < dateto && time530pm > datefrom /*|| time800am == datefrom || time530pm == dateto*/;

      if (isoverlap) {
        return true;
      }
      return false;
    },
    getDayTypes: function getDayTypes() {
      var isSittingDay = calenderdaysmap[this.form.duty_date] == "Sitting day";
      var isSittingOrWorkingDay = calenderdaysmap[this.form.duty_date].indexOf("oliday") == -1;
      var isWorkingDay = calenderdaysmap[this.form.duty_date].indexOf("orking") != -1;
      var isHoliDay = calenderdaysmap[this.form.duty_date].indexOf("oliday") != -1;
      return {
        isSittingDay: isSittingDay,
        isSittingOrWorkingDay: isSittingOrWorkingDay,
        isWorkingDay: isWorkingDay,
        isHoliDay: isHoliDay
      };
    },
    canShowAddlOT: function canShowAddlOT(row) {
      var isHoliDay = calenderdaysmap[this.form.duty_date].indexOf("oliday") != -1;
      if (!isHoliDay) return false;
      return row.designation == "Deputy Secretary" || row.designation == "Joint Secretary" || row.designation == "Additional Secretary" || row.designation == "Special Secretary";
    },
    overlapsWithOfficeHoursForNormalEmpl: function overlapsWithOfficeHoursForNormalEmpl(datefrom, dateto, isSittingDay, isWorkingDay) {
      var overlap = false;
      var checkingPeriod = "".concat(sNormalStart, " and ").concat(sNormalEnd);
      var sNormalStart = "10:00"; //for flexi
      var sNormalEnd = "17:30"; //"17:15".  in flexi time, 5.30 is the end time
      if (isSittingDay) {
        sNormalStart = "08:00";
        sNormalEnd = "17:30";
      }
      if (isWorkingDay || isSittingDay) {
        if (this.timePeriodsOverlap(datefrom, dateto, sNormalStart, sNormalEnd)) {
          overlap = true;
          return {
            overlap: overlap,
            checkingPeriod: checkingPeriod
          };
        }
      }
      return {
        overlap: overlap,
        checkingPeriod: checkingPeriod
      };
    },
    rowsvalid: function rowsvalid() {
      this.myerrors = [];
      this.errors = [];
      var self = this;
      if (self.form.session == "" || self.form.duty_date == "") {
        this.$swal("Error", "Please select session/date", "error");
        return false;
      }

      //check if date belongs to the session
      if (-1 == calenderdays2[self.form.session].indexOf(self.form.duty_date)) {
        this.$swal("Error", "The duty date is not a calender date for the session: " + self.form.session, "error");
        return false;
      }
      for (var i = 0; i < self.form.overtimes.length; i++) {
        var row = self.form.overtimes[i];
        if (row.pen == "" || row.designation == "" || row.from == "" || row.to == "" || row.from == null || row.to == null) {
          this.$swal("Row: " + (i + 1), "Fill all the fields in every row", "error");
          return false;
        }
        if (self.dayHasPunching && row.punching) {
          if (row.punchin == null || row.punchin == "" || row.punchout == null || row.punchout == "") {
            this.$swal("Row: " + (i + 1), "Punch in/out time not found", "error");
            //this.$swal("Row: " + (i + 1), "Fill punch in/out time for every row", "error");
            return false;
          }
        }
        if (!row.slots.length) {
          this.$swal("Error", "Please select the number of OTs", "error");
          return false;
        }
        if (this.hasAddl(row.slots) && !self.canShowAddlOT(row)) {
          this.$swal("Error", "Only DS or above can have Additional OT!", "error");
          return false;
        }

        //if user selects 1st and 3rd, but not 2nd, with continuous time, it does not make sense. they need to splitup into two forms
        if (this.hasFirst(row.slots) && (this.hasThird(row.slots) || this.hasAddl(row.slots)) && !this.hasSecond(row.slots)) {
          this.$swal("Error", "Can't have third OT without second", "error");
          return false;
        }
      }
      var _this$getDayTypes = this.getDayTypes(),
        isSittingDay = _this$getDayTypes.isSittingDay,
        isSittingOrWorkingDay = _this$getDayTypes.isSittingOrWorkingDay,
        isWorkingDay = _this$getDayTypes.isWorkingDay,
        isHoliDay = _this$getDayTypes.isHoliDay;

      //check time diff
      for (var i = 0; i < self.form.overtimes.length; i++) {
        var minothour_ideal = parseFloat(3);
        var minot_minutes = 180; //corrected to allow leeway 
        var daytypedesc = "holiday";
        if (isSittingOrWorkingDay) {
          minothour_ideal = parseFloat(2.5);
          minot_minutes = 150;
          daytypedesc = "working day";
          if (isSittingDay) {
            daytypedesc = "sitting day";
          }
        }
        var row = self.form.overtimes[i];

        //if(row.punching && self.dayHasPunching)  minot_minutes -= isSittingOrWorkingDay ? 5 : 5; //corrected to allow leeway 

        (0,_utils_js__WEBPACK_IMPORTED_MODULE_0__.setEmployeeTypes)(row);
        if (row.punching) {
          row.punchin = validateHhMm(row.punchin.trim());
          row.punchout = validateHhMm(row.punchout.trim());
        }
        row.from = validateHhMm(row.from.trim());
        row.to = validateHhMm(row.to.trim());
        if (row.from.toLowerCase() == "invalid date" || row.to.toLowerCase() == "invalid date") {
          row.from = row.to = "";
          this.myerrors.push("Row " + (i + 1) + ": Invalid time format. Enter (HH:MM) in 24 hour format ( examples: 09:30, 17:30).");
          return false;
        }
        if (!this.checkTimeWithinPunchingTime(row)) {
          this.myerrors.push("Row " + (i + 1) + ": OT period should be within punching times");
          return false;
        }

        //make sure our times are according to G.O
        //note same form can have both part time and full time empl. amspkr office
        if (isSittingDay) {
          if (!this.checkSittingDayTimeIsAsPerGO(row, i)) {
            return false;
          }
        }
        var _this$strTimesToDates4 = this.strTimesToDatesNormalized(row.from, row.to),
          datefrom = _this$strTimesToDates4.datefrom,
          dateto = _this$strTimesToDates4.dateto;
        //const momentfrom = moment(datefrom)
        //console.log('momentfrom: ' + momentfrom.format('HH:mm'))

        var otmins_actual = parseFloat((dateto - datefrom) / 60000);

        //add totalhours needed
        var otmins_practical = minot_minutes * row.slots.length; //if three OT, 3 * 2.5
        var othours_ideal = minothour_ideal * row.slots.length; //if three OT, 3 * 2.5
        if (isSittingOrWorkingDay && this.hasFirst(row.slots)) {
          //	console.log('oh: ' + this._daylenmultiplier);
          otmins_practical += row.normal_office_hours * 60 * this._daylenmultiplier;
          othours_ideal += row.normal_office_hours * this._daylenmultiplier;
        } else if (row.isNormal && isSittingDay) {
          //parttime does not have this type of adjustment where work before 8.00 am on sitting day
          //sec office on sitday works from 7 to 7 for 2 OT. let them enter the whole period
          var _this$overlapsWithOff = this.overlapsWithOfficeHoursForNormalEmpl(datefrom, dateto, isSittingDay, isWorkingDay),
            overlap = _this$overlapsWithOff.overlap,
            checkingPeriod = _this$overlapsWithOff.checkingPeriod;
          if (overlap) {
            //if there is overlap, we check the fulltime instead of just from 5.30
            var otmins_onsitday_includingsitOT = 570;
            //if(row.punching && self.dayHasPunching) { otmins_onsitday_includingsitOT -= 10 }
            otmins_practical += otmins_onsitday_includingsitOT * this._daylenmultiplier;
            othours_ideal += 9.5 * this._daylenmultiplier;
          } else
            //no overlap user has entered time from 5.30
            if (self.is11thOrLater && row.punching && self.dayHasPunching) {
              //if is11thOrLater we need to see if they are eligible for sitting OT. if so, make sure second ot starts from 5.30/5.40 pm if grace used

              var _eligibleForSitOTChec = (0,_utils_js__WEBPACK_IMPORTED_MODULE_0__.eligibleForSitOTCheck)(row.punchin, row.punchout),
                eligibleForSitOT = _eligibleForSitOTChec.eligibleForSitOT,
                graceMin = _eligibleForSitOTChec.graceMin;
              //if they are eligible for sit OT, 'from' need to start from 5.30+grace
              if (eligibleForSitOT) {
                var grace = graceMin;
                if (grace > 0) {
                  //if they are eligible for sit OT, 'from' need to start from 5.30+grace
                  //console.log('row.from: ' + row.from)
                  var momentfrom = moment.utc(datefrom);
                  //const otstartstarttime_req = moment(stringTimeToDate('17:30')).add(grace, 'minutes')
                  var otstartstarttime_req = (0,_utils_js__WEBPACK_IMPORTED_MODULE_0__.addMinutes)((0,_utils_js__WEBPACK_IMPORTED_MODULE_0__.stringTimeToDate)('17:30'), grace);
                  var momentfromreq = moment.utc(otstartstarttime_req);

                  //user will be claiming sitting OT later (auto calculated)
                  //so we need to make sure they are eligible for sitting OT
                  //if sit OT can be claimed, it will be from 5.30+grace. so dont allow second OT to start before that

                  console.log('momentfromreq: ' + momentfromreq.format('DD/MM/YYYY HH:mm'));
                  console.log('momentfrom: ' + momentfrom.format('DD/MM/YYYY HH:mm'));
                  if (momentfrom.isBefore(momentfromreq)) {
                    this.myerrors.push("Row  ".concat(i + 1, " :OT needs to start from ").concat(momentfromreq.format('HH:mm'), " on a sitting day"));
                    return false;
                  }
                }
              }
            }
        }
        console.log('otmins_needed: ' + otmins_practical);
        //new validation after adding normal_office_hours
        var diff = otmins_actual - otmins_practical;
        if (diff < 0) {
          var humandiff = (0,_utils_js__WEBPACK_IMPORTED_MODULE_0__.toHoursAndMinutes)(Math.abs(diff));
          this.myerrors.push("Row  ".concat(i + 1, " :Needs ").concat(othours_ideal, " hours for the selected OT(s) on a ").concat(daytypedesc, ". Diff=").concat(humandiff, " "));
          return false;
        }

        //partime emp cannot have 3rd OT on sitting and 2nd or 3rd ot on working days
        if (row.isPartime) {
          if (isSittingDay) {
            if (this.hasThird(row.slots)) {
              this.myerrors.push("Row " + (i + 1) + ": Parttime employees cannot have third OT on sitting day");
              return false;
            }
          } else if (isWorkingDay) {
            if (this.hasThird(row.slots) || this.hasSecond(row.slots)) {
              this.myerrors.push("Row " + (i + 1) + ": Parttime employees cannot have second/third OT on working day");
              return false;
            }
          }
        }

        //fulltime emp cannot have 2nd or 3rd OT on sitting/working days
        if (row.isFulltime) {
          if (isSittingOrWorkingDay) {
            if (this.hasThird(row.slots) || this.hasSecond(row.slots)) {
              this.myerrors.push("Row " + (i + 1) + ": FullTime employees cannot have second/third OT on working/sitting day");
              return false;
            }
          }
        }
        if (row.isNormal) {
          if ( /*isSittingDay ||*/isWorkingDay) {
            //if there is second, third, but no first
            if (!this.hasFirst(row.slots) && row.slots.length) {
              var _this$overlapsWithOff2 = this.overlapsWithOfficeHoursForNormalEmpl(datefrom, dateto, isSittingDay, isWorkingDay),
                _overlap = _this$overlapsWithOff2.overlap,
                _checkingPeriod = _this$overlapsWithOff2.checkingPeriod;
              if (_overlap) {
                this.myerrors.push("Row ".concat(i + 1, " : 2nd/3rd OT cannot overlap with ").concat(_checkingPeriod, " on a ").concat(daytypedesc));
                return false;
              }
            }
          }
          //check if there is time left for OT. if so this must be user careless or adding time from another section's time
          if (diff >= minot_minutes) {
            //this.myerrors.push(`Row ${i + 1} :Time left for another OT. If number of OT is correct, adjust from/to times accordingly`);
            //return false;
          }
        } else if (row.isPartime && isSittingDay) {
          //if there is second, third, but no first
          if (!this.hasFirst(row.slots) && row.slots.length) {
            var sNormalStart = "06:00";
            var sNormalEnd = "14:00"; //"11:30";
            //PT can be from 6-11:30 or 7-12:30
            //their 2nd OT is from 2-4.30. so dont allow them to enter whole period. only after 12.30
            //if this slot does not contain sitting ot on a sitting day 
            if (this.timePeriodsOverlap(datefrom, dateto, sNormalStart, sNormalEnd)) {
              this.myerrors.push("Row ".concat(i + 1, " : 2nd OT cannot be between ").concat(sNormalStart, " and ").concat(sNormalEnd, " on a ").concat(daytypedesc));
              return false;
            }
          }
        }
      }
      return this.checkDuplicates();
    },
    hasFirst: function hasFirst(slots) {
      return slots.includes("First");
    },
    hasSecond: function hasSecond(slots) {
      return slots.includes("Second");
    },
    hasThird: function hasThird(slots) {
      return slots.includes("Third");
    },
    hasAddl: function hasAddl(slots) {
      return slots.includes("Additional");
    },
    create: function create() {
      if (this.isProcessing) {
        //alert('no dbl click');
        return;
      }
      this.isProcessing = true;
      this.errors = [];
      if (!this.rowsvalid()) {
        this.isProcessing = false;
        return;
      }
      var self = this;
      if (self.form.overtimes.length <= 0) {
        //this.myerrors.push("Fill all the required fields!");
        this.$swal("Error", "Need at least one row!", "error");
        this.isProcessing = false;
        return false;
      }
      if (self.form.worknature == "") {
        this.$swal("Error", "Please enter the nature of work done", "error");
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
        self.$swal({
          type: "error",
          title: "Error",
          text: "Please see the error(s) shown in red at the top",
          timer: 2500
        });
        // alert('fail ajax');
        var response = error.response;
        self.isProcessing = false;
        self.errors = response.data;
      });
    },
    update: function update() {
      //  console.log('update 1')

      if (this.isProcessing) {
        return;
      }
      this.isProcessing = true;
      this.errors = [];
      if (!this.rowsvalid()) {
        this.isProcessing = false;
        return;
      }
      var self = this;
      if (self.form.overtimes.length <= 0) {
        this.$swal("Error", "Need at least one row!", "error");
        this.isProcessing = false;
        return false;
      }
      if (self.form.worknature == "") {
        this.$swal("Error", "Please enter the nature of work done", "error");
        this.isProcessing = false;
        return false;
      }

      //this.$swal('Please wait')
      //this.$swal.showLoading()

      var updateurl = urlformsubmit + "/" + self.form.id;

      // axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
      // axios.defaults.headers.common['X-CSRF-TOKEN'] = '{{csrf_token()}}';
      // axios.post('/overtime-allowance/public/admin/forms', self.form).then(response => {
      axios.put(updateurl, self.form).then(function (response) {
        //self.$swal.close();
        // alert('success ajax');
        if (response.data.created) {
          window.location.href = urlformsucessredirect + "/" + response.data.id;
        } else {
          self.isProcessing = false;
        }
      })["catch"](function (error) {
        self.$swal({
          type: "error",
          title: "Error",
          text: "Please read the error(s) shown in red at the top",
          timer: 2500
        });
        //self.$swal.close();
        var response = error.response;
        self.isProcessing = false;
        self.errors = response.data;
      });
    },
    savepreset: function savepreset() {
      var self = this;
      var pens = [];
      for (var i = 0; i < self.form.overtimes.length; i++) {
        var pen_name = self.form.overtimes[i].pen;
        if (pen_name != "") {
          pens.push(pen_name);
        }
      }
      if (pens.length == 0) {
        self.$swal("", "No rows to save", "error");
        return;
      }
      var obj = {};
      obj["pens"] = pens;
      obj["name"] = "default";
      self.$swal({
        text: "Enter a name for preset",
        input: "text",
        inputValue: "",
        showCancelButton: true,
        showLoaderOnConfirm: true,
        useRejections: true,
        inputValidator: function inputValidator(value) {
          return new Promise(function (resolve, reject) {
            if (value) {
              var found = presets.indexOf(value) > -1;
              if (found) {
                reject("Preset with same name exists!");
              } else {
                resolve();
              }
            } else {
              reject("You need to write something!");
            }
          });
        },
        preConfirm: function preConfirm(text) {
          return new Promise(function (resolve, reject) {
            obj["name"] = text;
            axios.post(urlpresetsubmit, obj).then(function (response) {
              if (response.data.result == true) {
                resolve();
              } else {
                reject(response.data.error);
              }
            })["catch"](function (error) {
              reject(error.response.data);
            });
          });
        }
      }).then(function (result) {
        self.$swal({
          type: "success",
          html: "Saved!",
          timer: 1500,
          useRejections: false
        });
      });
    },
    loadpreset: function loadpreset() {
      var self = this;
      if (presets.length == 0) {
        self.$swal("Sorry, no presets to load. Save a preset first");
        return;
      }
      self.$swal({
        text: "Load Preset",
        input: "select",
        inputOptions: presets,
        inputPlaceholder: "Select preset",
        showCancelButton: true,
        useRejections: false,
        inputValidator: function inputValidator(value) {
          return new Promise(function (resolve, reject) {
            if (value) {
              resolve();
            } else {
              reject("You need to select something)");
            }
          });
        },
        showLoaderOnConfirm: true,
        preConfirm: function preConfirm(index) {
          return new Promise(function (resolve, reject) {
            axios.get(urlajaxpresets + "/" + presets[index]).then(function (response) {
              self.loadpresetdata(response.data);
              resolve();
            })["catch"](function (error) {
              reject(error.response.data);
            });
          });
        }
      }).then(function (result) {}); //success
    },

    //loadpreset

    loadpresetdata: function loadpresetdata(obj) {
      var self = this;
      var timefrom = "";
      var timeto = "";
      //var worknature = self.presets_default["default_worknature"];

      if (this.form.overtime_slot != "") {
        //timefrom = def_time_start;
        //timeto = def_time_end;
      }
      if (self.form.overtimes.length > 0) {
        //timefrom = self.form.overtimes[0].from;
        //timeto = self.form.overtimes[0].to;
        //worknature = self.form.overtimes[0].worknature;
      }
      for (var key in obj) {
        if (obj.hasOwnProperty(key)) {
          //we can either clear items or we check for duplicates

          var index = -1;
          for (var i = 0; i < self.form.overtimes.length; i++) {
            var pen_name = self.form.overtimes[i].pen;
            if (pen_name == key) {
              index = i;
              break;
            }
          }
          if (index === -1) {
            self.form.overtimes.push({
              pen: key,
              designation: obj[key].desig,
              from: timefrom,
              to: timeto,
              //worknature: worknature,
              category: obj[key].category,
              employee_id: obj[key].employee_id,
              punching: obj[key].punching,
              normal_office_hours: obj[key].normal_office_hours,
              aadhaarid: obj[key].aadhaarid,
              slots: []
            });
            index = self.form.overtimes.length - 1;
          }
          this.fetchPunchingTimeForRow(index);
        }
      }
    },
    //loadpresetdata
    copytimedownonerow: function copytimedownonerow() {
      //console.log("copytimedownonerow");
      for (var i = 0; i < this.form.overtimes.length; i++) {
        if (this.form.overtimes[i].from == "" || this.form.overtimes[i].to == "") {
          this.form.overtimes[i].from = this.form.overtimes[i].punchin;
          this.form.overtimes[i].to = this.form.overtimes[i].punchout;
        }
      }
    },
    copyPunchFrom: function copyPunchFrom(row) {
      if (row.from == "") {
        row.from = row.punchin;
      }
    },
    copyPunchTo: function copyPunchTo(row) {
      if (row.to == "") {
        //row.from = row.punchin;
        row.to = row.punchout;
      }
    },
    subTime: function subTime(field, row) {
      var time = row[field];
      if (time == "") return;
      var momentObj = moment(time, ["HH:mm", "h:mm A"]);
      row[field] = momentObj.subtract(150, "minutes").format("HH:mm");
    },
    addTime: function addTime(field, row) {
      var time = row[field];
      if (time == "") return;
      var momentObj = moment(time, ["HH:mm", "h:mm A"]);
      row[field] = momentObj.add(150, "minutes").format("HH:mm");
    }
  } //methods
}); //vue
/*
window.addEventListener(
	"keydown",
	function (event) {
		if (event.defaultPrevented) {
			return; // Should do nothing if the default action has been cancelled
		}

		var handled = false;
		if (event.key !== undefined) {
			// Handle the event with KeyboardEvent.key and set handled true.
			if (event.key == "F4") {
				vm.copytimedownonerow();

				handled = true;
			} else if (event.key == "`") {
				//tilde

				vm.addRow();

				handled = true;
			}
		} else if (event.keyIdentifier !== undefined) {
			//alert(event.keyIdentifier);
			// Handle the event with KeyboardEvent.keyIdentifier and set handled true.
		} else if (event.keyCode !== undefined) {
			// Handle the event with KeyboardEvent.keyCode and set handled true.
		}

		if (handled) {
			// Suppress "double action" if event handled
			event.preventDefault();
		}
	},
	true
);
*/
})();

/******/ })()
;