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
/******/ 	return __webpack_require__(__webpack_require__.s = 43);
/******/ })
/************************************************************************/
/******/ ({

/***/ 43:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(44);


/***/ }),

/***/ 44:
/***/ (function(module, exports) {

var _methods;

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

var dateofdutyprefix = 'Date of Duty';

function validateHhMm(textval) {
    /*var isValid = /^([0-1]?[0-9]|2[0-3]):([0-5][0-9])$/.test(textval);
    if (isValid) {
        //inputField.style.backgroundColor = '#bfa';
    } else {
        //inputField.style.backgroundColor = '#fba';
    }
     return isValid;*/
    var momentObj = moment(textval, ["HH:mm", "h:mm A"]);
    return momentObj.format("HH:mm");
}

new Vue({

    el: '#app',

    data: {
        selectdaylabel: '', //dateofdutyprefix,
        isProcessing: false,
        form: {},
        errors: {},
        myerrors: [],
        // muloptions: designations,
        pen_names: [],

        configtime: {
            /*
              noCalendar: true,
             // wrap: true, // set wrap to true when using 'input-group'
              // dateFormat: "Y-m-d",
              enableTime: true, // locale for this instance only          
              minuteIncrement: 15,
              time_24hr : false,
            //  defaultHour : 17,
             // defaultMinute  :30,*/
            format: "HH:mm",
            stepping: 15

        }

        /* configdate: {
             dateFormat: 'd-m-Y',
            //enable: calenderdays2[this.data.form.session ]  
         }, */

    },

    created: function created() {
        Vue.set(this.$data, 'form', _form);

        //due to a bug, onchange is not called
        var self = this;
        this.$watch('form.duty_date', function (newVal, oldVal) {
            self.onChange();
        });
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
                enabledDates: Object.keys(calenderdaysmap).map(function (x) {
                    return moment(x, "DD-MM-YYYY").format('YYYY-MM-DD');
                })
            };
        },

        isActive: function isActive() {},
        slotoptions: function slotoptions() {

            if (this.form.duty_date.length == 0) return '';

            switch (calenderdaysmap[this.form.duty_date]) {

                case 'Sitting day':
                    if (SThirdOT) return ['Second', 'Third'];else return ['Second'];
                case undefined:
                    return '';

                default:
                    if (NSThirdOT) return ['First', 'Second', 'Third'];else if (NSSecondOT) return ['First', 'Second'];else return ['First'];
            }
        }
    },

    watch: {},

    methods: (_methods = {

        removeunchecked: function removeunchecked() {
            for (var i = 0; i < this.form.overtimes.length; i++) {
                if (!this.form.overtimes[i].checked) {
                    this.removeElement(i);
                    i--;
                }
            }
        },
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

            //this.configdate.enable =  calenderdays2[this.form.session]  
            this.myerrors = [];

            if (this.form.duty_date != '' && this.form.duty_date != null) {
                if (-1 == calenderdays2[this.form.session].indexOf(this.form.duty_date)) {

                    this.myerrors.push('For session ' + this.form.session + ', please select a date between : ' + calenderdays2[this.form.session][0] + ' and ' + calenderdays2[this.form.session][calenderdays2[this.form.session].length - 1] + '.');
                }
            }
        },

        onChange: function onChange() {
            this.myerrors = [];
            this.slotoptions = this.slotoptions;

            this.form.overtime_slot = '';

            if (this.form.duty_date != '' && this.form.duty_date != null) {

                if (calenderdaysmap[this.form.duty_date] !== undefined) this.selectdaylabel = ': ' + calenderdaysmap[this.form.duty_date];else this.selectdaylabel = ': Not valid for the session';

                if (-1 == calenderdays2[this.form.session].indexOf(this.form.duty_date)) {

                    this.myerrors.push('For session ' + this.form.session + ', please select a date between : ' + calenderdays2[this.form.session][0] + ' and ' + calenderdays2[this.form.session][calenderdays2[this.form.session].length - 1] + '.');
                }
            }
        },
        onChangeSlot: function onChangeSlot() {

            def_time_start = "17:30";
            def_time_end = "20:30";

            var isholiday = calenderdaysmap[this.form.duty_date].indexOf('oliday') != -1;

            if (!isholiday) {
                def_time_end = "20:00";
            }

            if (this.form.overtime_slot == 'First') {
                def_time_start = "14:30";
                def_time_end = "17:30";
            }

            if (this.form.overtimes.length > 0) {

                //this.$swal('Warning', 'You have added a few rows already. Please make sure the time-from and time-to of the rows are correct', 'warning')
                //clear all times
                for (var i = 0; i < this.form.overtimes.length; i++) {

                    this.form.overtimes[i].from = '';
                    this.form.overtimes[i].to = '';
                }

                //set first row time

                this.form.overtimes[0].from = def_time_start;
                this.form.overtimes[0].to = def_time_end;
            }
        },
        addRow: function addRow() {
            //  var elem = document.createElement('tr');
            var self = this;

            if (!this.rowsvalid()) {
                return;
            }

            var prevrow = self.form.overtimes.length > 0 ? self.form.overtimes[self.form.overtimes.length - 1] : null;

            self.form.overtimes.push({

                pen: "",
                //  designation: "",
                from: prevrow ? prevrow.from : def_time_start,
                to: prevrow ? prevrow.to : def_time_end,
                worknature: prevrow ? "-do-" : "",
                checked: false

            });

            this.pen_names = []; //clear previos selection from dropdown

            this.$nextTick(function () {
                self.$refs["field-" + (self.form.overtimes.length - 1)][0].$el.focus();
            });
        },

        removeElement: function removeElement(index) {
            this.form.overtimes.splice(index, 1);
        },

        limitText: function limitText(count) {
            return "and " + count + " more";
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
                    _this.pen_names = response.data;
                    //this.isLoading = false
                }).catch(function (response) {
                    alert(JSON.stringify(response.data)); // alerts {"myProp":"Hello"};
                });
            }
        },

        clearAll: function clearAll() {
            this.pen_names = [];
        }
    }, _defineProperty(_methods, "limitText", function limitText(count) {
        return "and " + count + " other countries";
    }), _defineProperty(_methods, "changeSelect", function changeSelect(index) {

        this.myerrors = [];
        //no need we will check on form submit
        //this also seems to display a warning when we
        //a. select a duplicate, b. change to a non duplicate immediately
        //that is not needed.
        //this.checkDuplicates()
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

        if (self.form.session == '' || self.form.duty_date == '' || self.form.overtime_slot == '') {
            //this.myerrors.push( 'Please select session/date/OT slot' )
            this.$swal('Oops', 'Please select session/date/OT slot', 'error');

            return false;
        }

        //check if date belongs to the session

        if (-1 == calenderdays2[self.form.session].indexOf(self.form.duty_date)) {
            this.$swal('Oops', 'The duty date is not within the range of dates for the session: ' + self.form.session, 'error');
            return false;
        }

        if (self.form.overtimes.some(function (row) {
            return row.pen == '' || /*row.designation == '' || */
            row.from == '' || row.to == '' || row.from == null || row.to == null;
        })) {
            //this.myerrors.push("Fill all the required fields!");
            this.$swal('Oops', "Fill all the required fields in every row!", 'error');
            return false;
        }

        //check time diff
        for (var i = 0; i < self.form.overtimes.length; i++) {

            self.form.overtimes[i].from = self.form.overtimes[i].from.trim();
            self.form.overtimes[i].to = self.form.overtimes[i].to.trim();

            /*self.form.overtimes[i].from = self.form.overtimes[i].from.replace('.', ':');
            self.form.overtimes[i].to = self.form.overtimes[i].to.replace('.', ':');
              if(!validateHhMm( self.form.overtimes[i].from) || 
               !validateHhMm( self.form.overtimes[i].to) ){
                this.myerrors.push( 'Row ' + (i+1) + ': Invalid time format. (HH:MM) in 24 hour format' )
               return false  
            }
            */
            self.form.overtimes[i].from = validateHhMm(self.form.overtimes[i].from);
            self.form.overtimes[i].to = validateHhMm(self.form.overtimes[i].to);
            if (self.form.overtimes[i].from.toLowerCase() == 'invalid date' || self.form.overtimes[i].to.toLowerCase() == 'invalid date') {
                self.form.overtimes[i].from = self.form.overtimes[i].to = '';
                this.myerrors.push('Row ' + (i + 1) + ': Invalid time format. Enter (HH:MM) in 24 hour format ( examples: 09:30, 17:30).');
                return false;
            }

            //date.parse returns number of milliseconds elapsed since 1970
            var time1 = self.form.overtimes[i].from.split(":").map(Number);
            var time2 = self.form.overtimes[i].to.split(":").map(Number);

            //warning: months in JS starts from 0
            var datefrom = Date.UTC(2000, 1, 1, time1[0], time1[1]);
            var dateto = Date.UTC(2000, 1, 1, time2[0], time2[1]);

            if (dateto <= datefrom) {
                dateto += 24 * 3600000;
            }

            var diffhours = (dateto - datefrom) / 3600000;

            var minothour = 3;
            var daytypedesc = 'holiday';

            if (calenderdaysmap[this.form.duty_date].indexOf('oliday') == -1) {
                //working day or sitting day
                minothour = 2.5;
                daytypedesc = 'sitting/working day';
            }

            if (diffhours < minothour) {
                this.myerrors.push('Row ' + (i + 1) + ': At least ' + minothour + ' hours needed for OT on a ' + daytypedesc);
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
            this.$swal('Oops', "Need at least one row!", 'error');
            this.isProcessing = false;
            return false;
        }

        //this.$swal('Please wait')
        //this.$swal.showLoading()

        // axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        // axios.defaults.headers.common['X-CSRF-TOKEN'] = '{{csrf_token()}}';
        // axios.post('/overtime-allowance/public/admin/forms', self.form).then(response => {
        axios.post(urlformsubmit, self.form).then(function (response) {
            //self.$swal.close();
            // alert('success ajax');
            if (response.data.created) {
                window.location.href = urlformsucessredirect + '/' + response.data.id;
            } else {
                self.isProcessing = false;
            }
        }).catch(function (error) {
            //self.$swal.close();
            self.$swal({
                type: 'error',
                title: 'Error',
                text: 'Please read the error(s) shown in red at the top',
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
        }).catch(function (error) {
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
    }), _defineProperty(_methods, "loadall", function loadall() {

        var self = this;

        axios.get(urlajaxpresets + '/all').then(function (response) {

            var obj = response.data;

            //alert (JSON.stringify(response.data))    // alerts {"myProp":"Hello"};

            for (var n = 0; n < obj.length; n++) {

                var key = obj[n];
                //we can either clear items or we check for duplicates
                var entryfound = false;
                for (var i = 0; i < self.form.overtimes.length; i++) {
                    var pen_name = self.form.overtimes[i].pen;
                    if (pen_name == key) {
                        entryfound = true;break;
                    }
                }

                //for (var j =0; j < 150; j++) 
                if (!entryfound) {
                    self.form.overtimes.push({
                        pen: key,
                        // designation: obj[key],
                        from: def_time_start,
                        to: def_time_end,
                        worknature: ""

                    });
                }
            }
        }).catch(function (error) {});
    }), _methods)

});

/***/ })

/******/ });