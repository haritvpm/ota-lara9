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
/******/ 	return __webpack_require__(__webpack_require__.s = 47);
/******/ })
/************************************************************************/
/******/ ({

/***/ 47:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(48);


/***/ }),

/***/ 48:
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
        muloptions: designations,
        pen_names: pa2mlas,
        pen_names_to_desig_our: pen_names_to_desig,
        addedemployeedesigdisplay: ''

    },

    created: function created() {
        Vue.set(this.$data, 'form', _form);
        this.sessionchanged();
        //due to a bug, onchange is not called
        /*var self = this
        this.$watch('form.session', function (newVal, oldVal) {
            self.sessionchanged();
        })*/

        /*for(var i=0; i < this.form.overtimes.length; i++){
           
           //copy if we have a name
           if(this.form.overtimes[i].name != null){
             this.form.overtimes[i].pen += '-' +this.form.overtimes[i].name ;
         }
            
        }*/
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
            //this.configdate.enable =  calenderdays2[this.form.session]                
            this.form.date_from = calenderdays2[this.form.session][0];
            this.form.date_to = calenderdays2[this.form.session][calenderdays2[this.form.session].length - 1];
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

            this.myerrors = [];

            var prevrow = self.form.overtimes.length > 0 ? self.form.overtimes[self.form.overtimes.length - 1] : null;

            self.form.overtimes.push({
                pen: "",
                designation: "Personal Assistant to MLA",

                count: prevrow ? prevrow.count : 0,
                worknature: prevrow ? prevrow.worknature : ""

            });

            this.$nextTick(function () {
                self.$refs["field-" + (self.form.overtimes.length - 1)][0].$el.focus();
            });
        },

        removeElement: function removeElement(index) {
            this.form.overtimes.splice(index, 1);
            this.myerrors = [];
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
        }

    }, _defineProperty(_methods, 'limitText', function limitText(count) {
        return 'and ' + count + ' other countries';
    }), _defineProperty(_methods, 'changeSelect', function changeSelect(selectedOption, id) {
        var _this2 = this;

        this.myerrors = [];
        var self = this;
        //alert('changin');
        self.$nextTick(function () {
            //for(var i=0; i < self.form.overtimes.length; i++)
            for (var i = self.form.overtimes.length - 1; i >= 0; i--) {
                if (self.form.overtimes[i].pen == selectedOption /*&& 
                                                                 self.form.overtimes[i].designation == ''*/) {

                        var desig = self.pen_names_to_desig_our[selectedOption];

                        if (desig !== undefined) {

                            if (desig.indexOf('Attendant') != -1) {

                                self.form.overtimes[i].designation = 'Office Attendant';
                                self.addedemployeedesigdisplay = '';
                            } else {
                                self.form.overtimes[i].designation = 'Personal Assistant to MLA';
                                var hiphenpos = self.form.overtimes[i].pen.indexOf('-');
                                self.addedemployeedesigdisplay = self.form.overtimes[i].pen.substr(hiphenpos + 1) + ' : ' + desig;
                                if (desig.toUpperCase().indexOf('RELIEVED') != -1) {
                                    _this2.myerrors.push('Emp relieved. Check the dates and OT manually: ' + self.form.overtimes[i].pen);
                                }
                            }
                        }
                        break;
                    }
            }
        });

        //no need we will check on form submit
        //this also seems to display a warning when we
        //a. select a duplicate, b. change to a non duplicate immediately
        //that is not needed.
        //this.checkDuplicates()
        /* var pen = self.pen_names[index]
         if(pen !== undefined){
           alert (self.pen_names_to_desig[pen])
          }*/
        //alert(id) unable to get id. so a hack
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

        if (-1 == calenderdays2[self.form.session].indexOf(self.form.date_from) || -1 == calenderdays2[self.form.session].indexOf(self.form.date_to)) {
            this.$swal('Oops', 'Please select a valid from-date/to-date for the selected session', 'error');
            return false;
        }

        if (self.form.overtimes.some(function (row) {
            return row.pen == '' || row.designation == '' || +row.count <= 0;
        })) {

            //this.myerrors.push("Fill all the required fields!");
            this.$swal('Oops', "Fill all the required fields!", 'error');
            return false;
        }
        var totaldays = calenderdays2[this.form.session].length;
        if (self.form.overtimes.some(function (row) {
            return +row.count > totaldays;
        })) {

            this.myerrors.push("Total days cannot be more than " + totaldays);
            return false;
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

        //this.$swal('Please wait')
        //this.$swal.showLoading()

        axios.post(urlformsubmit, self.form).then(function (response) {
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
    }), _defineProperty(_methods, 'update', function update() {

        if (!this.rowsvalid()) {
            return;
        }

        var self = this;

        if (self.form.overtimes.length <= 0) {

            //this.myerrors.push("Fill all the required fields!");
            this.$swal('Oops', "Need at least one row!", 'error');
            return false;
        }

        this.isProcessing = true;
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
    }), _methods)

});

/***/ })

/******/ });