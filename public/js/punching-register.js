/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!**************************************************!*\
  !*** ./resources/assets/js/punching-register.js ***!
  \**************************************************/
var vm = new Vue({
  el: '#app',
  data: {
    section_employees: null,
    date: null,
    selectdaylabel: "",
    //dateofdutyprefix,
    section: '*'
  },
  created: function created() {
    Vue.set(this.$data, "section_employees", section_employees);
    console.log(this.section_employees);
  },
  mounted: function mounted() {},
  watch: {},
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
        maxDate: new Date()
      };
    },
    section_employees_selected: function section_employees_selected() {
      var self = this;
      return this.section_employees.filter(function (s) {
        return s.id == self.section || self.section == '*';
      });
    }
  },
  // define methods under the `methods` object
  methods: {
    onChange: function onChange(e) {
      console.log('date cha');
    },
    sectionchanged: function sectionchanged(e) {
      console.log('sec cha');
      console.log(this.section);
    }
  }
});
/******/ })()
;