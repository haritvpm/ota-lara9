/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*****************************************!*\
  !*** ./resources/assets/js/punching.js ***!
  \*****************************************/
var _methods;
function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function _defineProperty(obj, key, value) { key = _toPropertyKey(key); if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }
var dateofdutyprefix = 'Date of Duty';
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
  el: '#app',
  data: {
    isProcessing: false,
    form: {},
    errors: {},
    myerrors: [],
    muloptions: designations,
    pen_names: [],
    pen_names_to_desig: [],
    configtime: {
      /*vue-flatpickr
        noCalendar: true,
       // wrap: true, // set wrap to true when using 'input-group'
        // dateFormat: "Y-m-d",
        enableTime: true, // locale for this instance only          
        minuteIncrement: 15,
        time_24hr : false,
      //  defaultHour : 17,
       // defaultMinute  :30,
       */

      format: "HH:mm",
      stepping: 15
    }

    // configdate: {

    //   dateFormat: 'd-m-Y',
    //   //enable: calenderdays2[this.data.form.session ]  
    // },
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

    //due to a bug, onchange is not called
    var self = this;
    this.$watch('form.pen', function (newVal, oldVal) {
      self.onChange();
    });
  },
  mounted: function mounted() {

    // alert(JSON.stringify(presets_default)); 
    //alert(JSON.stringify(presets_default));                      
  },
  computed: {
    configdate: function configdate() {
      var self = this;
      return {
        //dateFormat: 'd-m-Y',
        //enable: calenderdays2[self.form.session]

        //
        format: 'DD-MM-YYYY',
        useCurrent: false,
        //we have to convert the keys (dates) in calenderdaysmap to YYYY-MM-DD format
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
      // alert(JSON.stringify((calenderdays2[this.form.session])));
      //this.configdate.enabledDates =  Object.keys(calenderdaysmap)
      this.myerrors = [];
    },
    onChange: function onChange() {
      this.myerrors = [];
    },
    addRow: function addRow() {
      this.insertElement(this.form.punchings.length);
    },
    insertElement: function insertElement(index) {
      var self = this;
      if (!this.rowsvalid()) {
        return;
      }
      this.myerrors = [];
      var prevrow = index > 0 && self.form.punchings.length >= index ? self.form.punchings[index - 1] : null;
      this.form.punchings.splice(index, 0, {
        date: "",
        punchin: "",
        punchout: ""
      });
      this.$nextTick(function () {
        self.$refs["field-" + index][0].$el.focus();
      });
    },
    removeElement: function removeElement(index) {
      /*if(this.form.overtimes[index].pen == '' || 
         confirm("Remove this row?"))*/
      {
        this.form.punchings.splice(index, 1);
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
        axios.get(urlajaxpen + '/' + query).then(function (response) {
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
    return "and ".concat(count, " other countries");
  }), _defineProperty(_methods, "changeSelect", function changeSelect(selectedOption, id) {
    this.myerrors = [];
    var self = this;
    //alert('changin');
    self.$nextTick(function () {
      //for(var i=0; i < self.form.punchings.length; i++)

      if (self.form.pen == selectedOption /*&& 
                                          self.form.punchings[i].designation == ''*/) {
        var desig = self.pen_names_to_desig[selectedOption];
        if (desig !== undefined) {
          //self.form.designation = desig
        }
      }
    });
  }), _defineProperty(_methods, "updateSelect", function updateSelect(selectedOption, id) {
    //not workin
    //alert('updated');
  }), _defineProperty(_methods, "checkDuplicates", function checkDuplicates() {
    var self = this;
    //see if there are duplicates
    var obj = {};
    for (var i = 0; i < self.form.punchings.length; i++) {
      if (obj[self.form.punchings[i].date] == undefined) {
        obj[self.form.punchings[i].date] = true;
      } else {
        this.myerrors.push('Duplicate date found: ' + self.form.ovepunchingsrtimes[i].date);
        return false;
      }
    }
    return true;
  }), _defineProperty(_methods, "rowsvalid", function rowsvalid() {
    this.myerrors = [];
    var self = this;
    if (self.form.session == '' || self.form.pen == '') {
      //this.myerrors.push( 'Please select session/date/OT slot' )
      this.$swal('Oops', 'Please select session/pen', 'error');
      return false;
    }

    //check if date belongs to the session

    // if (-1 == calenderdays2[self.form.session].indexOf(self.form.duty_date)) {
    //   this.$swal('Oops', 'The duty date is not within the range of dates for the session: ' + self.form.session, 'error')
    //   return false
    // }

    for (var i = 0; i < self.form.punchings.length; i++) {
      var row = self.form.punchings[i];
      if (row.date == '' || row.punchin == '' || row.punchout == '') {
        this.$swal('Row: ' + (i + 1), "Fill all the fields in every row", 'error');
        return false;
      }
    }

    //check time diff
    for (var i = 0; i < self.form.overtimes.length; i++) {
      self.form.punchings[i].punchin = self.form.punchings[i].punchin.trim();
      self.form.punchings[i].punchout = self.form.punchings[i].punchout.trim();
      self.form.punchings[i].punchin = validateHhMm(self.form.punchings[i].punchin);
      self.form.punchings[i].punchout = validateHhMm(self.form.punchings[i].punchout);
      if (self.form.punchings[i].punchin.toLowerCase() == 'invalid date' || self.form.punchings[i].punchout.toLowerCase() == 'invalid date') {
        self.form.punchings[i].punchin = self.form.punchings[i].punchout = '';
        this.myerrors.push('Row ' + (i + 1) + ': Invalid time format. Enter (HH:MM) in 24 hour format ( examples: 09:30, 17:30).');
        return false;
      }

      //make sure our times are according to G.O if this is 2nd or 3rd ot on a sitting day
      //note same form can have both part time and full time empl. amspkr office
      /*
      if (!iswatchnward &&
        calenderdaysmap[this.form.duty_date] == 'Sitting day') {
         if (self.form.overtimes[i].designation.toLowerCase().indexOf("part time") != -1) {
          //parttime emp
           if (self.form.overtime_slot == 'Second') {
             if (self.form.overtimes[i].from != "14:00" || self.form.overtimes[i].to != "16:30") {
               this.myerrors.push('Row ' + (i + 1) + ': Parttime employees time should be as per G.O on a sitting day')
              return false
             }
          } else {
            this.myerrors.push('Row ' + (i + 1) + ': Parttime employees cannot have third OT on a sitting day')
            return false
          }
         }
        else //all other employees and full time
        {
           //no need to enforce ending time. have doubts regarding mla hostel. 
          //need to check night shifts
           if ((self.form.overtime_slot == 'Second' && (self.form.overtimes[i].from != "17:30" ))
            ||
            (self.form.overtime_slot == 'Third' && (self.form.overtimes[i].from != "20:00"))) {
             this.myerrors.push('Row ' + (i + 1) + ': Time should be as per G.O on a sitting day')
            return false
           }
         }
       }
      */
    }

    return this.checkDuplicates();
  }), _defineProperty(_methods, "create", function create() {
    if (this.isProcessing) {
      //alert('no dbl click');
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
      this.$swal('Oops', "Need at least one row!", 'error');
      this.isProcessing = false;
      return false;
    }
    axios.post(urlformsubmit, self.form).then(function (response) {
      //self.$swal.close();
      // alert('success ajax');
      if (response.data.created) {
        window.location.href = urlformsucessredirect + '/' + response.data.id;
      } else {
        self.isProcessing = false;
      }
    })["catch"](function (error) {
      self.$swal({
        type: 'error',
        title: 'Error',
        text: 'Please see the error(s) shown in red at the top',
        timer: 2500
      });
      // alert('fail ajax');
      var response = error.response;
      self.isProcessing = false;
      // alert (JSON.stringify(response.data));    // alerts {"myProp":"Hello"};
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
      this.$swal('Oops', "Need at least one row!", 'error');
      this.isProcessing = false;
      return false;
    }

    //this.$swal('Please wait')
    //this.$swal.showLoading()

    var updateurl = urlformsubmit + '/' + self.form.id;

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
        type: 'error',
        title: 'Error',
        text: 'Please read the error(s) shown in red at the top',
        timer: 2500
      });
      //self.$swal.close();
      var response = error.response;
      self.isProcessing = false;
      // alert (JSON.stringify(response.data));    // alerts {"myProp":"Hello"};
      // Vue.set(self.$data, 'errors', response.data);
      self.errors = response.data;
    });
  }), _methods) //methods
}); //vue

window.addEventListener("keydown", function (event) {
  if (event.defaultPrevented) {
    return; // Should do nothing if the default action has been cancelled
  }

  var handled = false;
  if (event.key !== undefined) {
    // Handle the event with KeyboardEvent.key and set handled true.
    if (event.key == 'F4') {
      vm.copytimedownonerow();
      handled = true;
    } else if (event.key == '`') {
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