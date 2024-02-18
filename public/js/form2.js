/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};
/*!**************************************!*\
  !*** ./resources/assets/js/form2.js ***!
  \**************************************/


var _methods;
function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function _defineProperty(obj, key, value) { key = _toPropertyKey(key); if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }
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
      if (this.form.duty_date.length == 0) return "";
      if (!ispartimefulltime && !iswatchnward) {
        switch (calenderdaysmap[this.form.duty_date]) {
          case "Sitting day":
            return ["First", "Second", "Third"];
          case "Prior holiday":
          case "Holiday":
            return ["First", "Second", "Third", "Additional"];
          case undefined:
            return "";
          default:
            return ["First", "Second", "Third"];
        }
      } else {
        return ["First", "Second"];
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
      return this.form.duty_date ? calenderdaypunching[this.form.duty_date] === 'MANUALENTRY' : false;
    }
  },
  watch: {},
  methods: (_methods = {
    copytimedown: function copytimedown() {
      if (this.form.overtimes.length > 1) {
        for (var i = 1; i < this.form.overtimes.length; i++) {
          this.form.overtimes[i].from = this.form.overtimes[0].from;
          this.form.overtimes[i].to = this.form.overtimes[0].to;
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

      //alert(JSON.stringify((calenderdays2[this.form.session])));
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
      if ((e === null || e === void 0 ? void 0 : e.type) != 'dp') return; //this func seems to be called twice on date change. this prevents that as the first call does not have that set

      this.updateDateDependencies();
      if (this.form.duty_date != "" && this.form.duty_date != null) {
        if (e.oldDate != e.date) {
          this.fetchPunching();
        }
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
    onChangeSlot: function onChangeSlot() {
      this.myerrors = [];
      if (this.form.overtimes.length > 0 && this.form.overtimes[0].from == "") {
        //do this only if first row of a slot is empty

        //clear all times
        for (var i = 0; i < this.form.overtimes.length; i++) {
          this.form.overtimes[i].from = "";
          this.form.overtimes[i].to = "";
        }
      }
    },
    fetchPunching: function fetchPunching() {
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
    }
  }, _defineProperty(_methods, "limitText", function limitText(count) {
    return "and ".concat(count, " other ");
  }), _defineProperty(_methods, "setEmployeeTypes", function setEmployeeTypes(row) {
    row.isPartime = row.designation.toLowerCase().indexOf("part time") != -1;
    row.isFulltime = row.category.toLowerCase().indexOf("fulltime") != -1;
    row.isWatchnward = row.category.toLowerCase().indexOf("watch") != -1;
  }), _defineProperty(_methods, "changeSelect", function changeSelect(selectedOption, id) {
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
        _this2.setEmployeeTypes(row);
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
  }), _defineProperty(_methods, "fetchPunchingTimeForRow", function fetchPunchingTimeForRow(index) {
    var _this3 = this;
    var self = this;
    var row = self.form.overtimes[index];
    if (row.pen == "" || !self.form.duty_date) return;
    //set punchtime if not set and available
    //reset for example if user selects another person after selecting a person with punchtime
    // self.form.overtimes[id].allowpunch_edit=true;
    row.punchin = "";
    row.punchout = "";
    row.punching_id = null;
    if (self.dayHasPunching) {
      axios.get(urlajaxgetpunchtimes + "/" + self.form.duty_date + "/" + row.pen + "/" + row.aadhaarid).then(function (response) {
        //console.log("got punch data");
        //console.log(response);
        if (response.data && response.data.hasOwnProperty("punchin") && response.data.hasOwnProperty("punchout")) {
          //console.log("set punch data");
          row.punchin = response.data.punchin;
          row.punchout = response.data.punchout;
          row.aadhaarid = response.data.aadhaarid;
          row.punching_id = response.data.id;

          //remove after testing
          row.from = response.data.punchin;
          row.to = response.data.punchout;

          //vue does not update time if we change date as it does not watch for array changes
          //https://v2.vuejs.org/v2/guide/reactivity#Change-Detection-Caveats
          Vue.set(_this3.form.overtimes, index, row);
        }
      })["catch"](function (err) {});
    }
  }), _defineProperty(_methods, "checkDuplicates", function checkDuplicates() {
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
  }), _defineProperty(_methods, "stringTimeToDate", function stringTimeToDate(sTimeWithSemicolonSeperator) {
    var time = sTimeWithSemicolonSeperator.split(":").map(Number);
    //warning: months in JS starts from 0
    return Date.UTC(2000, 1, 1, time[0], time[1]);
  }), _defineProperty(_methods, "strTimesToDatesNormalized", function strTimesToDatesNormalized(from, to) {
    var datefrom = this.stringTimeToDate(from);
    var dateto = this.stringTimeToDate(to);

    //time after 12 am ?
    if (dateto <= datefrom) {
      dateto += 24 * 3600000;
    }
    return {
      datefrom: datefrom,
      dateto: dateto
    };
  }), _defineProperty(_methods, "checkTimeWithinPunchingTime", function checkTimeWithinPunchingTime(row) {
    if (!row.punching) return true;
    var _this$strTimesToDates = this.strTimesToDatesNormalized(row.from, row.to),
      datefrom = _this$strTimesToDates.datefrom,
      dateto = _this$strTimesToDates.dateto;
    var _this$strTimesToDates2 = this.strTimesToDatesNormalized(row.punchin, row.punchout),
      datepunchin = _this$strTimesToDates2.datefrom,
      datepunchout = _this$strTimesToDates2.dateto;
    if (datepunchin > datefrom || datepunchout < dateto) {
      return false;
    }
    return true;
  }), _defineProperty(_methods, "checkSittingDayTimeIsAsPerGO", function checkSittingDayTimeIsAsPerGO(row, i) {
    //we need to give some leeway. so commenting

    if (row.isPartime) {
      //parttime emp
      /*
      if (overtime_slot == "First") {
      if (row.from != "06:00" || row.to != "11:30") {
      this.myerrors.push("Row " + (i + 1) + ": Parttime employee - time shall be 06:00 to 11:30 as per G.O on a sitting day");
      return false;
      }
      } else if (overtime_slot == "Second") {
      //no need to strict time. let them decide for themselves.
      if (row.from != "14:00" || row.to != "16:30") {
      this.myerrors.push("Row " + (i + 1) + ": Parttime employee - time shall be 14:00 to 16:30 as per G.O on a sitting day");
      return false;
      }
      } else {
      //no third OT. we check that in parent function
      }
      */
    } else if (row.isFulltime) {
      /*
      if (overtime_slot == "First") {
      if (row.from != "06:00" 
        //|| row.to != "16:30"
        ) {
      this.myerrors.push("Row " + (i + 1) + ": Fulltime employee - time shall be from 06:00 a.m. as per G.O on a sitting day");
      return false;
      }
      }*/
      //no second, third OT. we check that in parent function
    } else if (row.isWatchnward) {} //all other employees for sitting days
    else {
      //no need to enforce ending time. have doubts regarding mla hostel.
      //need to check night shifts
      //let diffFrom = null
      //let diffTo = null
      //const diffdatefunc = (t1, t2) => Math.round((this.stringTimeToDate(t1) - this.stringTimeToDate(t2)) / 60000); 

      if (this.hasFirst(row.slots)) {
        var datefrom = this.stringTimeToDate(row.from);
        var dateto = this.stringTimeToDate(row.to);
        var time800am = this.stringTimeToDate("08:05"); // a flexy time of 5 mins eitherway
        var time530pm = this.stringTimeToDate("17:25");
        var isok = time800am >= datefrom && time530pm <= dateto;
        if (!isok) {
          this.myerrors.push("Row " + (i + 1) + ": For sitting OT, time should include 08:00 to 17:30 as per GO");
          return false;
        }
      }
    }
    return true;
  }), _defineProperty(_methods, "checkIf2ndOTOverlapsWithOfficeHours", function checkIf2ndOTOverlapsWithOfficeHours(datefrom, dateto, sNormalStart, sNormalEnd) {
    var time800am = this.stringTimeToDate(sNormalStart);
    var time530pm = this.stringTimeToDate(sNormalEnd);
    var isoverlap = time800am < dateto && time530pm > datefrom || time800am == datefrom || time530pm == dateto;
    if (isoverlap) {
      return false;
    }
    return true;
  }), _defineProperty(_methods, "getDayTypes", function getDayTypes() {
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
  }), _defineProperty(_methods, "canShowAddlOT", function canShowAddlOT(row) {
    var isHoliDay = calenderdaysmap[this.form.duty_date].indexOf("oliday") != -1;
    if (!isHoliDay) return false;
    return row.designation == "Deputy Secretary" || row.designation == "Joint Secretary" || row.designation == "Additional Secretary" || row.designation == "Special Secretary";
  }), _defineProperty(_methods, "rowsvalid", function rowsvalid() {
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
    var minothour_ideal = parseFloat(3);
    var minothour = parseFloat(2.8); //corrected to allow leeway 12 minutes
    var daytypedesc = "holiday";
    if (isSittingOrWorkingDay) {
      minothour_ideal = parseFloat(2.5);
      minothour = parseFloat(2.4); //6 min leeway
      daytypedesc = "working day";
      if (isSittingDay) {
        daytypedesc = "sitting day";
      }
    }

    //check time diff
    for (var i = 0; i < self.form.overtimes.length; i++) {
      var row = self.form.overtimes[i];
      console.log(row);
      this.setEmployeeTypes(row);
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
      var _this$strTimesToDates3 = this.strTimesToDatesNormalized(row.from, row.to),
        datefrom = _this$strTimesToDates3.datefrom,
        dateto = _this$strTimesToDates3.dateto;
      var diffhours = parseFloat((dateto - datefrom) / 3600000);

      //add totalhours needed
      var othours_practical = minothour * row.slots.length; //if three OT, 3 * 2.5
      var othours_ideal = minothour_ideal * row.slots.length; //if three OT, 3 * 2.5
      if (isSittingOrWorkingDay && this.hasFirst(row.slots)) {
        console.log('oh: ' + this._daylenmultiplier);
        othours_practical += row.normal_office_hours * this._daylenmultiplier;
        othours_ideal += row.normal_office_hours * this._daylenmultiplier;
      }
      console.log('difneeded: ' + othours_practical);
      console.log('dif: ' + diffhours);
      //new validation after adding normal_office_hours
      if (diffhours < othours_practical) {
        this.myerrors.push("Row  ".concat(i + 1, " : At least ").concat(othours_ideal, " hours needed for the selected OT(s) on a ").concat(daytypedesc));
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
      if (!row.isPartime && !row.isFulltime && !row.isWatchnward && !isspeakeroffice) {
        if (isSittingDay || isWorkingDay) {
          //if there is second, third, but no first
          if (!this.hasFirst(row.slots) && row.slots.length) {
            var sNormalStart = "10:15";
            var sNormalStartWithGrace = "10:20";
            var sNormalEnd = "17:15";
            var sNormalEndWithGrace = "17:10";
            if (isSittingDay) {
              sNormalStart = "08:00";
              sNormalStartWithGrace = "08:05";
              sNormalEnd = "17:30";
              sNormalEndWithGrace = "17:25";
            }
            //if this slot does not contain sitting ot on a sitting day and first ot on a working day show error
            if (!this.checkIf2ndOTOverlapsWithOfficeHours(datefrom, dateto, sNormalStartWithGrace, sNormalEndWithGrace)) {
              this.myerrors.push("Row ".concat(i + 1, " : 2nd/3rd OT cannot be between ").concat(sNormalStart, " and ").concat(sNormalEnd, " am on a ").concat(daytypedesc));
              return false;
            }
          }
        }
      }
    }
    return this.checkDuplicates();
  }), _defineProperty(_methods, "hasFirst", function hasFirst(slots) {
    return slots.includes("First");
  }), _defineProperty(_methods, "hasSecond", function hasSecond(slots) {
    return slots.includes("Second");
  }), _defineProperty(_methods, "hasThird", function hasThird(slots) {
    return slots.includes("Third");
  }), _defineProperty(_methods, "hasAddl", function hasAddl(slots) {
    return slots.includes("Additional");
  }), _defineProperty(_methods, "create", function create() {
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
  }), _defineProperty(_methods, "update", function update() {
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
  }), _defineProperty(_methods, "savepreset", function savepreset() {
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
  }), _defineProperty(_methods, "loadpreset", function loadpreset() {
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
  }), _defineProperty(_methods, "loadpresetdata", function loadpresetdata(obj) {
    var self = this;
    var timefrom = "";
    var timeto = "";
    //var worknature = self.presets_default["default_worknature"];

    if (this.form.overtime_slot != "") {
      timefrom = def_time_start;
      timeto = def_time_end;
    }
    if (self.form.overtimes.length > 0) {
      timefrom = self.form.overtimes[0].from;
      timeto = self.form.overtimes[0].to;
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
            slots: []
          });
          index = self.form.overtimes.length - 1;
        }
        this.fetchPunchingTimeForRow(index);
      }
    }
  }), _defineProperty(_methods, "copytimedownonerow", function copytimedownonerow() {
    console.log("copytimedownonerow");
    for (var i = 0; i < this.form.overtimes.length - 1; i++) {
      if (this.form.overtimes[i].from != "" && this.form.overtimes[i].to != "" && this.form.overtimes[i + 1].from == "" && this.form.overtimes[i + 1].to == "") {
        this.form.overtimes[i + 1].from = this.form.overtimes[i].from;
        this.form.overtimes[i + 1].to = this.form.overtimes[i].to;
        break;
      }
    }
  }), _methods) //methods
}); //vue

window.addEventListener("keydown", function (event) {
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
}, true);
/******/ })()
;