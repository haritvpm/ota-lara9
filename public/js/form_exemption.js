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
/******/ 	return __webpack_require__(__webpack_require__.s = 39);
/******/ })
/************************************************************************/
/******/ ({

/***/ 39:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(40);


/***/ }),

/***/ 40:
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
        pen_names_to_desig: []

    },

    created: function created() {
        Vue.set(this.$data, 'form', _form);
        this.sessionchanged();
    },
    mounted: function mounted() {},

    computed: {

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

        addRow: function addRow() {
            //  var elem = document.createElement('tr');
            var self = this;

            if (!this.rowsvalid()) {
                return;
            }

            var prevrow = self.form.exemptions.length > 0 ? self.form.exemptions[self.form.exemptions.length - 1] : null;

            self.form.exemptions.push({
                pen: "",
                designation: "",

                worknature: ""

            });

            this.pen_names = []; //clear previos selection from dropdown
            this.pen_names_to_desig = [];

            this.$nextTick(function () {
                self.$refs["field-" + (self.form.exemptions.length - 1)][0].$el.focus();
            });
        },

        removeElement: function removeElement(index) {
            if (this.form.exemptions[index].pen == '' || confirm("Remove this row?")) {
                this.myerrors = [];
                this.form.exemptions.splice(index, 1);
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

            for (var i = 0; i < self.form.exemptions.length; i++) {
                if (self.form.exemptions[i].pen == selectedOption) {
                    var desig = self.pen_names_to_desig[selectedOption];
                    //added no change if a desig already exists
                    //to prevent an issue where designation is changeed was wrong
                    //try with vince - vincent prasad and dr vincent
                    if (desig !== undefined
                    /*&& self.form.exemptions[i].designation == null*/) {
                            //let them enter the correct designation.
                            //self.form.exemptions[i].designation = desig

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
        for (var i = 0; i < self.form.exemptions.length; i++) {
            if (obj[self.form.exemptions[i].pen] == undefined) {
                obj[self.form.exemptions[i].pen] = true;
            } else {
                this.myerrors.push('Duplicate name found: ' + self.form.exemptions[i].pen);
                return false;
            }
        }

        return true;
    }), _defineProperty(_methods, 'rowsvalid', function rowsvalid() {

        this.myerrors = [];

        var self = this;

        if (self.form.session == '') {
            //this.myerrors.push( 'Please select session/dates' )
            this.$swal('Oops', "Please select session!", 'error');
            return false;
        }

        if (calenderdays2[self.form.session] == undefined) {
            this.$swal('Oops', 'Session calender not valid', 'error');
            return false;
        }

        /*
            if( -1 == calenderdays2[self.form.session].indexOf(self.form.date_from) ||
                -1 == calenderdays2[self.form.session].indexOf(self.form.date_to))
            {
              this.$swal('Oops', 'Please select a valid from-date/to-date for the selected session', 'error')
              return false 
            }
              //check if date from less than date to
             {
                //date.parse returns number of milliseconds elapsed since 1970
                var date1 = self.form.date_from.split("-").map(Number);
                var date2 = self.form.date_to.split("-").map(Number);
                 //warning: months in JS starts from 0
                var datefrom    = new Date( date1[2], date1[1]-1,date1[0]);
                var dateto      = new Date( date2[2], date2[1]-1,date2[0]);
                 if( datefrom > dateto ){ //it can be equial though
                    //this.myerrors.push( 'Date-from cannot be greater than Date-to')
                    this.$swal('Oops', "Date-from cannot be greater than Date-to!", 'error')
                    return false
                }
             }
          */

        for (var i = 0; i < self.form.exemptions.length; i++) {

            var row = self.form.exemptions[i];
            if (row.pen == '' || row.designation == '' || row.worknature == '') {

                //this.myerrors.push("Fill all the required fields!");
                this.$swal('Row: ' + (i + 1), "Fill all the required fields in each row!", 'error');
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
        if (self.form.exemptions.length <= 0) {

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
            console.log(error.response);
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

        if (self.form.exemptions.length <= 0) {

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
            console.log(error.response);
            //self.$swal.close();
            var response = error.response;
            self.isProcessing = false;
            // alert (JSON.stringify(response.data));    // alerts {"myProp":"Hello"};
            // Vue.set(self.$data, 'errors', response.data);
            self.errors = response.data;
        });
    }), _methods)

});

/***/ })

/******/ });