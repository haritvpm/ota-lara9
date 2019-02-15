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
/******/ 	return __webpack_require__(__webpack_require__.s = 37);
/******/ })
/************************************************************************/
/******/ ({

/***/ 37:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(38);


/***/ }),

/***/ 38:
/***/ (function(module, exports) {

var _methods;

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

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
        presets: presets,
        calenderdays2: calenderdays2
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

            self.form.overtimes.push({
                pen: "",
                designation: "",
                from: self.form.date_from,
                to: self.form.date_to,
                count: "",
                worknature: ""

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
            }
        },

        limitText: function limitText(count) {
            return 'and ' + count + ' more';
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
                }).catch(function (response) {
                    // alert (JSON.stringify(response.data))    // alerts {"myProp":"Hello"};

                });
            }
        },

        clearAll: function clearAll() {
            this.pen_names = [];
            this.pen_names_to_desig = [];
        }
    }, _defineProperty(_methods, 'limitText', function limitText(count) {
        return 'and ' + count + ' other countries';
    }), _defineProperty(_methods, 'changeSelect', function changeSelect(selectedOption) {

        this.myerrors = [];
        var self = this;

        self.$nextTick(function () {

            //for(var i=0; i < self.form.overtimes.length; i++)
            for (var i = self.form.overtimes.length - 1; i >= 0; i--) {
                if (self.form.overtimes[i].pen == selectedOption) {
                    var desig = self.pen_names_to_desig[selectedOption];
                    //added no change if a desig already exists
                    //to prevent an issue where designation is changeed was wrong
                    //try with vince - vincent prasad and dr vincent
                    if (desig !== undefined
                    /*&& self.form.overtimes[i].designation == null*/) {
                            self.form.overtimes[i].designation = desig;

                            //self.$forceUpdate()
                        }
                    break;
                }
            }
        });
    }), _defineProperty(_methods, 'checkDuplicates', function checkDuplicates() {
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
    }), _defineProperty(_methods, 'rowsvalid', function rowsvalid() {

        this.myerrors = [];

        var self = this;

        if (self.form.session == '' || self.form.date_from == '' || self.form.date_to == '') {
            //this.myerrors.push( 'Please select session/dates' )
            this.$swal('Oops', "Please select session/dates!", 'error');
            return false;
        }

        if (calenderdays2[self.form.session] == undefined) {
            this.$swal('Oops', 'Session calender not valid', 'error');
            return false;
        }

        if (-1 == calenderdays2[self.form.session].indexOf(self.form.date_from) || -1 == calenderdays2[self.form.session].indexOf(self.form.date_to)) {
            this.$swal('Oops', 'Please select a valid from-date/to-date for the selected session', 'error');
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
                this.$swal('Oops', "Date-from cannot be greater than Date-to!", 'error');
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
    }), _defineProperty(_methods, 'create', function create() {

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

        axios.post(urlformsubmit, self.form).then(function (response) {
            //self.$swal.close();
            // alert('success ajax');
            if (response.data.created) {
                window.location.href = urlformsucessredirect + "/" + response.data.id;
            } else {
                self.isProcessing = false;
            }
        }).catch(function (error) {
            //console.log( error.response );
            //self.$swal.close();
            //self.$swal('Oops', "!", 'error')
            self.$swal({
                type: 'error',
                title: 'Error',
                text: 'Please read the error(s) shown in red',
                timer: 2500

            });

            var response = error.response;
            self.isProcessing = false;
            // alert (JSON.stringify(response.data));    // alerts {"myProp":"Hello"};
            // Vue.set(self.$data, 'errors', response.data);
            self.errors = response.data;
        });
    }), _defineProperty(_methods, 'update', function update() {

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

        var updateurl = urlformsubmit + '/' + self.form.id;

        axios.put(updateurl, self.form).then(function (response) {
            //self.$swal.close();
            // alert('success ajax');
            if (response.data.created) {
                window.location.href = urlformsucessredirect + "/" + response.data.id;;
            } else {
                self.isProcessing = false;
            }
        }).catch(function (error) {
            //console.log( error.response );
            self.$swal({
                type: 'error',
                title: 'Error',
                text: 'Please read the error(s) shown in red',
                timer: 2500

            });
            //self.$swal.close();
            var response = error.response;
            self.isProcessing = false;
            // alert (JSON.stringify(response.data));    // alerts {"myProp":"Hello"};
            // Vue.set(self.$data, 'errors', response.data);
            self.errors = response.data;
        });
    }), _defineProperty(_methods, 'loadpreset', function loadpreset() {

        var self = this;

        if (presets.length == 0) {
            self.$swal('Sorry, no presets to load.');
            return;
        }

        self.$swal({
            text: 'Load Preset',
            input: 'select',
            inputOptions: presets,
            inputPlaceholder: 'Select preset',
            showCancelButton: true,
            useRejections: false,
            inputValidator: function inputValidator(value) {
                return new Promise(function (resolve, reject) {
                    if (value) {
                        resolve();
                    } else {
                        reject('You need to select something)');
                    }
                });
            },
            showLoaderOnConfirm: true,
            preConfirm: function preConfirm(index) {
                return new Promise(function (resolve, reject) {

                    axios.get(urlajaxpresets + '/' + presets[index]).then(function (response) {

                        var obj = response.data;

                        for (var key in obj) {
                            if (obj.hasOwnProperty(key)) {

                                //we can either clear items or we check for duplicates
                                var entryfound = false;
                                for (var i = 0; i < self.form.overtimes.length; i++) {
                                    var pen_name = self.form.overtimes[i].pen;
                                    if (pen_name == key) {
                                        entryfound = true;break;
                                    }
                                }

                                if (!entryfound) {
                                    self.form.overtimes.push({
                                        pen: key,
                                        designation: obj[key],
                                        from: self.form.date_from,
                                        to: self.form.date_to,
                                        count: "",
                                        worknature: ""

                                    });
                                }
                            }
                        }

                        resolve();
                    }).catch(function (error) {

                        reject(error.response.data);
                    });
                });
            }

        }).then(function (result) {}); //success 
    }), _methods)

});

/***/ })

/******/ });