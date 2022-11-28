/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!***********************************************!*\
  !*** ./resources/assets/js/employee_index.js ***!
  \***********************************************/
var vm = new Vue({
  el: '#app',
  data: {
    empdesig: '',
    empname: '',
    emppen: '',
    designations: designations
  },
  mounted: function mounted() {},
  watch: {
    emppen: function emppen() {
      this.empname = '';
      this.empdesig = '';
      if (this.emppen.length >= 6) {
        this.asyncFind();
      }
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
    }, 500),
    mydelayedsearch: function mydelayedsearch(query) {
      var app = this;
      app.empname = 'Searching...';
      //if(query.length >= 6)
      {
        axios.get(urlajaxpen + '/' + query).then(function (response) {
          if (response.data.pen_names.length) {
            app.empname = response.data.pen_names[0];

            //app.designations = response.data.designations;
            app.empdesig = response.data.pen_names_to_desig[app.empname];
          }
        })["catch"](function (response) {
          //alert (JSON.stringify(response.data))    // alerts {"myProp":"Hello"};
          app.empname = 'unknown';
          app.empdesig = '';
        });
      }
    },
    changeSelect: function changeSelect(selectedOption, id) {}
  }
});
/******/ })()
;