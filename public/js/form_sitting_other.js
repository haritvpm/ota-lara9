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
/******/ 	return __webpack_require__(__webpack_require__.s = 45);
/******/ })
/************************************************************************/
/******/ ({

/***/ 45:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(46);


/***/ }),

/***/ 46:
/***/ (function(module, exports) {

var _methods;

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

new Vue({

    el: '#app',

    data: {

        isProcessing: false,
        form: {},
        errors: {},
        myerrors: [],
        //muloptions: designations,
        pen_names: []
    },

    created: function created() {
        Vue.set(this.$data, 'form', _form);
        this.sessionchanged();
    },
    mounted: function mounted() {},

    computed: {
        configdate: function configdate() {
            var self = this;
            return {
                //dateFormat: 'd-m-Y',
                //enable: calenderdays2[self.form.session]  
                format: 'DD-MM-YYYY'
            };
        },

        isActive: function isActive() {}

    },

    watch: {},

    methods: (_methods = {

        sessionchanged: function sessionchanged() {
            if (calenderdays2[this.form.session] != undefined) {
                //this.configdate.enable =  calenderdays2[this.form.session]                
                this.form.date_from = calenderdays2[this.form.session][0];
                this.form.date_to = calenderdays2[this.form.session][calenderdays2[this.form.session].length - 1];
            } else {
                this.form.date_from = '';
                this.form.date_to = '';
            }
        },

        onChange: function onChange() {
            this.myerrors = [];
            this.slotoptions = this.slotoptions;
            this.selectdaylabel = ': ' + calenderdaysmap[this.form.duty_date];
            this.form.overtime_slot = '';
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
                from: prevrow ? prevrow.from : self.form.date_from,
                to: prevrow ? prevrow.to : self.form.date_to,
                count: "",
                worknature: ""

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
            return 'and ' + count + ' more';
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
                //axios.headers.common['X-CSRF-TOKEN'] = '{{csrf_token()}}';
                //axios.get('/overtime-allowance/public/admin/employees/ajaxfind/'+ query).then(response => {
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
    }, _defineProperty(_methods, 'limitText', function limitText(count) {
        return 'and ' + count + ' other countries';
    }), _defineProperty(_methods, 'changeSelect', function changeSelect(index) {
        this.myerrors = [];
        //this.checkDuplicates() //causes issue on change 
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

        if (self.form.overtimes.some(function (row) {
            return row.pen == '' || /*row.designation == '' || */
            row.from == '' || row.to == '' || row.from == null || row.to == null || +row.count <= 0 || isNaN(+row.count);
        })) {

            //this.myerrors.push("Fill all the required fields!");
            this.$swal('Oops', "Fill all the required fields in each row!", 'error');
            return false;
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

        // axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        // axios.defaults.headers.common['X-CSRF-TOKEN'] = '{{csrf_token()}}';
        // axios.post('/overtime-allowance/public/admin/forms', self.form).then(response => {
        axios.post(urlformsubmit, self.form).then(function (response) {
            //console.log( response.data );
            //self.$swal.close();
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

        this.isProcessing = true;
        //this.$swal('Please wait')
        //this.$swal.showLoading()


        var updateurl = urlformsubmit + '/' + self.form.id;

        axios.put(updateurl, self.form).then(function (response) {
            //console.log( response.data );
            //self.$swal.close();
            if (response.data.created) {
                window.location.href = urlformsucessredirect + "/" + response.data.id;;
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
    }), _defineProperty(_methods, 'loadall', function loadall() {

        var self = this;
        var prevrow = self.form.overtimes.length > 0 ? self.form.overtimes[self.form.overtimes.length - 1] : null;

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

                if (!entryfound) {
                    self.form.overtimes.push({

                        pen: key,

                        from: prevrow ? prevrow.from : self.form.date_from,
                        to: prevrow ? prevrow.to : self.form.date_to,
                        count: "",
                        worknature: ""

                    });
                }
            }
        }).catch(function (error) {});
    }), _methods)

});

/***/ })

/******/ });