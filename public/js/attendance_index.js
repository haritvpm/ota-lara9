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
/******/ 	return __webpack_require__(__webpack_require__.s = 57);
/******/ })
/************************************************************************/
/******/ ({

/***/ 57:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(58);


/***/ }),

/***/ 58:
/***/ (function(module, exports) {


var vm = new Vue({
  el: '#app',
  data: {

    empname: '',
    emppen: '',
    sitting_date: moment(new Date(), "DD MM YYYY").format("DD-MM-YYYY"),
    sitting_date_display: '',
    list: [],
    myerrors: [],
    mysuccess: []
  },

  mounted: function mounted() {

    this.datechange();
  },
  watch: {
    emppen: function emppen() {
      this.empname = '';
      this.list = [];
      this.myerrors = [];
      this.mysuccess = [];
      if (this.emppen.length >= 3) {
        this.asyncFind();
      }
    }
    // sitting_date: function() {

    //   //  this.sitting_date_display = moment(this.sitting_date,"DD MM YYYY").format("DD-MM-YYYY");

    // },

  },
  computed: {
    configdate: function configdate() {
      var self = this;

      return {
        //dateFormat: 'd-m-Y',
        //enable: calenderdays2[self.form.session]

        //
        format: 'DD-MM-YYYY',
        useCurrent: true,
        showTodayButton: true,
        maxDate: new Date(),

        //we have to convert the keys (dates) in calenderdaysmap to YYYY-MM-DD format
        enabledDates: Object.keys(calenderdaysmap).map(function (x) {
          return moment(x, "DD-MM-YYYY").format('YYYY-MM-DD');
        })

      };
    }
  },

  // define methods under the `methods` object
  methods: {

    asyncFind: _.debounce(function () {
      var app = this;

      //  this.isLoading = true
      // Make a request for a user with a given ID
      app.empname = 'Searching...';
      this.mydelayedsearch(app.emppen.trim());
    }, 300),

    mydelayedsearch: function mydelayedsearch(query) {

      var app = this;
      app.list = [];
      this.myerrors = [];
      this.mysuccess = [];

      if (!calenderdaysmap.hasOwnProperty(this.sitting_date)) {
        this.myerrors.push('Please select a sitting day');
        return;
      }

      if (query.length >= 3) {
        app.empname = 'Searching...';

        var sendobj = app.emppen + '|' + moment(app.sitting_date, "DD MM YYYY").format("DD-MM-YYYY");

        axios.get(urlajaxpen + '/' + sendobj).then(function (response) {

          if (response.data.pen_names.length) {

            for (var i = 0; i < response.data.pen_names.length; i++) {
              app.list.push({
                'name': response.data.pen_names[i],
                'desig': response.data.pen_names_to_desig[response.data.pen_names[i]],
                'absent': response.data.pen_names_to_absent[response.data.pen_names[i]]
              });
            }

            //app.designations = response.data.designations;
            //alert (JSON.stringify(app.list)) 
            // alert (JSON.stringify(response.data.pen_names_to_absent)) 
          } else {
            //app.empname = 'not found'
            app.myerrors.push(app.emppen + ' not found');
          }
        }).catch(function (response) {
          //alert (JSON.stringify(response.data))    // alerts {"myProp":"Hello"};
          //app.empname = 'unknown'
          app.myerrors.push(app.emppen + ' not found');
        });
      }
    },
    datechange: function datechange() {

      // alert(this.sitting_date);
      if (calenderdaysmap.hasOwnProperty(this.sitting_date)) {

        if (moment().diff(moment(this.sitting_date, "DD MM YYYY"), "days") != 0) //prevent diff if today
          {
            this.sitting_date_display = moment(this.sitting_date, "DD MM YYYY").fromNow() + " (session: " + calenderdaysmap[this.sitting_date] + ")";
          } else {
          this.sitting_date_display = "Today (session: " + calenderdaysmap[this.sitting_date] + ")";
        }
      } else {
        this.sitting_date_display = 'Not a sitting day';
      }

      this.emppen = '';
      this.myerrors = [];
      this.mysuccess = [];
    },

    mark: function mark(index) {

      var app = this;
      this.myerrors = [];
      this.mysuccess = [];

      var sendobj = app.list[index].name + '|' + moment(app.sitting_date, "DD MM YYYY").format("DD-MM-YYYY");

      axios.get(urlajaxpenupdate + '/' + sendobj).then(function (response) {

        if (response.data) {
          if (response.data.res) {
            if (response.data.absent) {
              app.myerrors.push(app.list[index].name + ' marked as absent/late');
            } else {
              app.mysuccess.push(app.list[index].name + ' marked as present');
            }
            app.list = [];
            app.list.push({
              'name': response.data.name,
              'desig': response.data.desig,
              'absent': response.data.absent
            });
          } else {
            app.myerrors.push(app.list[index].name + ' unable to change attendance');
          }
        } else {
          //alert('fail');
          app.emppen = '';
        }
      }).catch(function (response) {
        //alert (JSON.stringify(response.data))    

      });
    } //mark

  }
});

/***/ })

/******/ });