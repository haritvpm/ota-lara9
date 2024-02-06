
const dateofdutyprefix = 'Date of Duty';

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

  var momentObj = moment(textval, ["HH:mm", "h:mm A"])
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

      stepping: 15,

    },

    // configdate: {

    //   dateFormat: 'd-m-Y',
    //   //enable: calenderdays2[this.data.form.session ]  
    // },

  },

  created: function () {
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
    var self = this
    this.$watch('form.pen', function (newVal, oldVal) {
      self.onChange();
    })

 

  },
  mounted: function () {

    // alert(JSON.stringify(presets_default)); 
    //alert(JSON.stringify(presets_default));                      

  },

  computed: {
    configdate: function () {
      var self = this


      return {
        //dateFormat: 'd-m-Y',
        //enable: calenderdays2[self.form.session]

        //
        format: 'DD-MM-YYYY',
        useCurrent: false,

        //we have to convert the keys (dates) in calenderdaysmap to YYYY-MM-DD format
        enabledDates: Object.keys(calenderdaysmap).map(x => moment(x, "DD-MM-YYYY").format('YYYY-MM-DD')),


      }
    },

    isActive: function () {

    },

  },

  watch: {

  },

  methods: {


    sessionchanged: function () {
      // alert(JSON.stringify((calenderdays2[this.form.session])));
      //this.configdate.enabledDates =  Object.keys(calenderdaysmap)
      this.myerrors = [];



    },

    onChange: function () {

      this.myerrors = [];



    },


    addRow: function () {
      this.insertElement(this.form.punchings.length)

    },

    insertElement: function (index) {
      var self = this;

      if (!this.rowsvalid()) {
        return
      }

      this.myerrors = [];
      var prevrow = (index > 0 && self.form.punchings.length >= index) ? self.form.punchings[index - 1] : null;

      this.form.punchings.splice(index, 0, {
        date: "",

        punchin: "",
        punchout: "",

      });

      this.$nextTick(() => {
        self.$refs["field-" + index][0].$el.focus()
      })

    },

    removeElement: function (index) {

      /*if(this.form.overtimes[index].pen == '' || 
         confirm("Remove this row?"))*/
      {

        this.form.punchings.splice(index, 1);
        this.myerrors = [];
      }
    },

    limitText(count) {
      return `and ${count} more`
    },

    asyncFind: _.debounce(function (query) {
      //  this.isLoading = true
      // Make a request for a user with a given ID
      this.mydelayedsearch(query);
      this.myerrors = [];
    }, 500),

    mydelayedsearch: function (query) {

      if (query.length >= 3) {
        axios.get(urlajaxpen + '/' + query).then(response => {

          // console.log(response.data);
          this.pen_names = response.data.pen_names;
          this.pen_names_to_desig = response.data.pen_names_to_desig;
          //this.isLoading = false
          //alert (JSON.stringify(this.pen_names_to_desig))
        })
          .catch(response => {
            // alert (JSON.stringify(response.data))    // alerts {"myProp":"Hello"};

          });
      }

    },

    clearAll() {
      this.pen_names = []
      this.pen_names_to_desig = []
    },

    limitText(count) {
      return `and ${count} other countries`
    },

    changeSelect(selectedOption, id) {

      this.myerrors = [];
      var self = this
      //alert('changin');
      self.$nextTick(() => {
        //for(var i=0; i < self.form.punchings.length; i++)

        if (self.form.pen == selectedOption /*&& 
                     self.form.punchings[i].designation == ''*/) {

          var desig = self.pen_names_to_desig[selectedOption];
          if (desig !== undefined) {
            //self.form.designation = desig

          }

        }


      })




    },
    updateSelect(selectedOption, id) {
      //not workin
      //alert('updated');

    },
    checkDuplicates() {

      var self = this
      //see if there are duplicates
      var obj = {}
      for (var i = 0; i < self.form.punchings.length; i++) {
        if (obj[self.form.punchings[i].date] == undefined) {
          obj[self.form.punchings[i].date] = true
        } else {
          this.myerrors.push('Duplicate date found: ' + self.form.ovepunchingsrtimes[i].date)
          return false
        }
      }

      return true

    },

    rowsvalid() {


      this.myerrors = [];


      var self = this



      if (self.form.session == '' || self.form.pen == '') {
        //this.myerrors.push( 'Please select session/date/OT slot' )
        this.$swal('Oops', 'Please select session/pen', 'error')

        return false
      }

      //check if date belongs to the session

      // if (-1 == calenderdays2[self.form.session].indexOf(self.form.duty_date)) {
      //   this.$swal('Oops', 'The duty date is not within the range of dates for the session: ' + self.form.session, 'error')
      //   return false
      // }


      for (var i = 0; i < self.form.punchings.length; i++) {

        var row = self.form.punchings[i];
        if (row.date == '' || row.punchin == '' ||
          row.punchout == '') {
          this.$swal('Row: ' + (i + 1), "Fill all the fields in every row", 'error')
          return false
        }
      }

      //check time diff
      for (var i = 0; i < self.form.overtimes.length; i++) {


        self.form.punchings[i].punchin = self.form.punchings[i].punchin.trim();
        self.form.punchings[i].punchout = self.form.punchings[i].punchout.trim();

        self.form.punchings[i].punchin = validateHhMm(self.form.punchings[i].punchin)
        self.form.punchings[i].punchout = validateHhMm(self.form.punchings[i].punchout)

        if (self.form.punchings[i].punchin.toLowerCase() == 'invalid date' ||
          self.form.punchings[i].punchout.toLowerCase() == 'invalid date') {
          self.form.punchings[i].punchin = self.form.punchings[i].punchout = ''
          this.myerrors.push('Row ' + (i + 1) + ': Invalid time format. Enter (HH:MM) in 24 hour format ( examples: 09:30, 17:30).')
          return false
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

      return this.checkDuplicates()
    },

    create: function () {

      if (this.isProcessing) {
        //alert('no dbl click');
        return;
      }

      this.isProcessing = true;


      if (!this.rowsvalid()) {
        this.isProcessing = false;
        return
      }

      var self = this
      if (self.form.overtimes.length <= 0) {

        //this.myerrors.push("Fill all the required fields!");
        this.$swal('Oops', "Need at least one row!", 'error')
        this.isProcessing = false;
        return false
      }


      axios.post(urlformsubmit, self.form).then(response => {
        //self.$swal.close();
        // alert('success ajax');
        if (response.data.created) {
          window.location.href = urlformsucessredirect + '/' + response.data.id;
        } else {
          self.isProcessing = false;
        }
      })
        .catch(error => {
          self.$swal({
            type: 'error',
            title: 'Error',
            text: 'Please see the error(s) shown in red at the top',
            timer: 2500,

          })
          // alert('fail ajax');
          const response = error.response
          self.isProcessing = false;
          // alert (JSON.stringify(response.data));    // alerts {"myProp":"Hello"};
          // Vue.set(self.$data, 'errors', response.data);
          self.errors = response.data

        })

    },

    update: function () {

      if (this.isProcessing) {
        return;
      }

      this.isProcessing = true;

      if (!this.rowsvalid()) {
        this.isProcessing = false;
        return
      }

      var self = this
      if (self.form.overtimes.length <= 0) {

        this.$swal('Oops', "Need at least one row!", 'error')
        this.isProcessing = false;
        return false
      }



      //this.$swal('Please wait')
      //this.$swal.showLoading()


      var updateurl = urlformsubmit + '/' + self.form.id

      // axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
      // axios.defaults.headers.common['X-CSRF-TOKEN'] = '{{csrf_token()}}';
      // axios.post('/overtime-allowance/public/admin/forms', self.form).then(response => {
      axios.put(updateurl, self.form).then(response => {
        //self.$swal.close();
        // alert('success ajax');
        if (response.data.created) {

          window.location.href = urlformsucessredirect + "/" + response.data.id;
        } else {
          self.isProcessing = false;

        }
      })
        .catch(error => {
          self.$swal({
            type: 'error',
            title: 'Error',
            text: 'Please read the error(s) shown in red at the top',
            timer: 2500,

          })
          //self.$swal.close();
          const response = error.response
          self.isProcessing = false;
          // alert (JSON.stringify(response.data));    // alerts {"myProp":"Hello"};
          // Vue.set(self.$data, 'errors', response.data);
          self.errors = response.data
        })

    },









  } //methods

}) //vue


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
    }
    else
      if (event.key == '`') { //tilde

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


