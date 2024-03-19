
var vm = new Vue({
    el: '#app',
    data: {
        section_employees : null,
        date: null,
        selectdaylabel: "", //dateofdutyprefix,
        section : '*',
      },
  
	created: function () {
		Vue.set(this.$data, "section_employees", section_employees);
	    console.log(this.section_employees)
	},
      mounted: function () {
         
       
         
      }, 
      watch: {
       
         
      },
       computed: {
                  configdate:  function() {
                      var self = this
                                        
                      return {
                          //dateFormat: 'd-m-Y',
                          //enable: calenderdays2[self.form.session]
  
                          //
                        format: 'DD-MM-YYYY',
                        useCurrent: true,
                        showTodayButton : true,
                        maxDate : new Date(),
 
                      }
                  },
      },
     
        // define methods under the `methods` object
      methods: {
        onChange: function (e) {
			console.log('date cha')
		},
        sectionchanged: function (e) {
			console.log('sec cha')
			console.log(this.section)
            
		},
      }
  })
  