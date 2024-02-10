
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
    selectdaylabel: '',//dateofdutyprefix,
    isProcessing: false,
    form: {},
    errors: {},
    myerrors: [],
    muloptions: designations,
    pen_names: [],
    pen_names_to_desig: [],
    presets: presets,
    presets_default: presets_default,


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




    /* configdate: {

        dateFormat: 'd-m-Y',
        //enable: calenderdays2[this.data.form.session ]  
     }, */

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
    this.$watch('form.duty_date', function (newVal, oldVal) {
      self.onChange();
    })

    if (autoloadpens) {
      this.loadpresetdata(autoloadpens)
    }

  },
  mounted: function () {

    // alert(JSON.stringify(presets_default)); 
    //alert(JSON.stringify(presets_default));                      
    //this.$refs.multiselectRef.$refs.search.setAttribute("autocomplete", "off")
   // console.log( this.$refs.multiselectRef )
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
    slotoptions: function () {

      if (this.form.duty_date.length == 0)
        return '';

      if (!ispartimefulltime) {
        switch (calenderdaysmap[this.form.duty_date]) {

          case 'Sitting day':
            return ['First', 'Second', 'Third'];

          case 'Prior holiday':
          case 'Holiday':
            return ['First', 'Second', 'Third', 'Additional'];
          case undefined:
            return '';
          default:
            return ['First', 'Second', 'Third'];
        }
      } else {
        switch (calenderdaysmap[this.form.duty_date]) {

          case 'Sitting day':
            return ['First', 'Second'];

          default:
            return ['First', 'Second'];
        } //switch
      }


    }
  },

  watch: {

  },

  methods: {

    copytimedown: function () {
      if (this.form.overtimes.length > 1) {

        for (var i = 1; i < this.form.overtimes.length; i++) {

          this.form.overtimes[i].from = this.form.overtimes[0].from
          this.form.overtimes[i].to = this.form.overtimes[0].to


        }
      }
    },
    copyworknaturedown: function () {
      if (this.form.overtimes.length > 1) {

        for (var i = 1; i < this.form.overtimes.length; i++) {

          this.form.overtimes[i].worknature = this.form.overtimes[0].worknature

        }
      }
    },
    sessionchanged: function () {
      // alert(JSON.stringify((calenderdays2[this.form.session])));
      //this.configdate.enabledDates =  Object.keys(calenderdaysmap)
      this.myerrors = [];

      //this.form.duty_date =  '00-08-2017' //calenderdays2[this.form.session][0];


      if (this.form.duty_date != '' && this.form.duty_date != null) {
        if (-1 == calenderdays2[this.form.session].indexOf(this.form.duty_date)) {

          this.myerrors.push('For session ' + this.form.session + ', please select a date between : ' +
            calenderdays2[this.form.session][0] + ' and ' +
            calenderdays2[this.form.session][calenderdays2[this.form.session].length - 1] + '.');

        }
      }

    },

    onChange: function () {

      this.myerrors = [];
      this.slotoptions = this.slotoptions
      this.form.overtime_slot = ''

      if (this.form.duty_date != '' && this.form.duty_date != null) {

        if (calenderdaysmap[this.form.duty_date] !== undefined)
          this.selectdaylabel = ': ' + calenderdaysmap[this.form.duty_date]
        else
          this.selectdaylabel = ': Not valid for the session'

        if (-1 == calenderdays2[this.form.session].indexOf(this.form.duty_date)) {

          this.myerrors.push('For session ' + this.form.session + ', please select a date between : ' +
            calenderdays2[this.form.session][0] + ' and ' +
            calenderdays2[this.form.session][calenderdays2[this.form.session].length - 1] + '.');

        }
      }

    },
    onChangeSlot: function () {

      this.myerrors = [];

      var daytype = calenderdaysmap[this.form.duty_date]
      if (daytype !== undefined) {
        daytype = daytype.toLowerCase()
      } else {
        daytype = "undefined";
      }



      def_time_start = "17:30";
      def_time_end = "20:00";

      if (this.form.overtime_slot == 'First') {

        if (daytype.indexOf('sitting') !== -1) { //sitting day

          def_time_start = "8:00";
          def_time_end = "17:30";

          //todo fulltime its 6-4.30
          if (ispartimefulltime) {
            def_time_start = "6:00";
            def_time_end = "11:30";
          }

        }
        else
          if (daytype.indexOf('working') != -1 && daytype.indexOf('holiday') == -1) { //working day
            //working day
            //sitting day, no first
            def_time_start = "10:15";
            def_time_end = "19:45";

            if (presets_default) {
              if (presets_default.hasOwnProperty('default_workingday_firstot_starttime') &&
                presets_default.hasOwnProperty('default_workingday_firstot_endtime')) {
                def_time_start = presets_default['default_workingday_firstot_starttime'];
                def_time_end = presets_default['default_workingday_firstot_endtime'];;
              }
            }

          }
          else {
            //holiday 
            def_time_start = "14:30";
            def_time_end = "17:30";
            if (presets_default) {
              if (presets_default.hasOwnProperty('default_holiday_firstot_starttime') &&
                presets_default.hasOwnProperty('default_holiday_firstot_endtime')) {
                def_time_start = presets_default['default_holiday_firstot_starttime'];
                def_time_end = presets_default['default_holiday_firstot_endtime'];;
              }
            }


          }


      }
      /////////SECOND////
      else if (this.form.overtime_slot == 'Second') {

        if (daytype.indexOf('sitting') != -1) { //sitting

          //sitting day, no first
          def_time_start = "17:30";
          def_time_end = "20:00";

          if (presets_default) {
            if (presets_default.hasOwnProperty('default_sittingday_secondot_starttime') &&
              presets_default.hasOwnProperty('default_sittingday_secondot_endtime')) {
              def_time_start = presets_default['default_sittingday_secondot_starttime'];
              def_time_end = presets_default['default_sittingday_secondot_endtime'];;
            }
          }

        }
        else if (daytype.indexOf('working') != -1 && daytype.indexOf('holiday') == -1) //working
        {
          //working
          def_time_start = "19:45";
          def_time_end = "22:15";

          if (presets_default) {
            if (presets_default.hasOwnProperty('default_workingday_secondot_starttime') &&
              presets_default.hasOwnProperty('default_workingday_secondot_endtime')) {
              def_time_start = presets_default['default_workingday_secondot_starttime'];
              def_time_end = presets_default['default_workingday_secondot_endtime'];;
            }
          }

        }
        else if (daytype.indexOf('holiday') != -1) //holiday
        {
          //holiday
          def_time_start = "17:30";
          def_time_end = "20:30";

          if (presets_default) {
            if (presets_default.hasOwnProperty('default_holiday_secondot_starttime') &&
              presets_default.hasOwnProperty('default_holiday_secondot_endtime')) {
              def_time_start = presets_default['default_holiday_secondot_starttime'];
              def_time_end = presets_default['default_holiday_secondot_endtime'];;
            }
          }
        }


      }
      ////////Third////////
      else if (this.form.overtime_slot == 'Third') {

        def_time_start = "20:30";
        def_time_end = "23:30";
        if (daytype.indexOf('sitting') != -1) { //sitting
          def_time_start = "20:00";
          def_time_end = "22:30";
        }
        else
          if (daytype.indexOf('holiday') == -1) //working day, not holiday
          {  //not holiday

            // def_time_start = "";
            // def_time_end = "";
          }


      }
      else if (this.form.overtime_slot == 'Additional') { //only on holidays
        def_time_start = "10:30";
        def_time_end = "13:30";
      }

      //}
      // else { //parttime

      //   def_time_start = "07:00"; //non sitting first
      //   def_time_end = "13:00";

      //   if( this.form.overtime_slot == 'Second') {
      //       def_time_start = "14:00"; //sitting second for PTS
      //       def_time_end = "17:00"; //for FTS, no 2nd
      //   }

      // }


      if (this.form.overtimes.length > 0 && this.form.overtimes[0].from == '') {

        //do this only if first row of a slot is empty


        //this.$swal('Warning', 'You have added a few rows already. Please make sure the time-from and time-to of the rows are correct', 'warning')
        //clear all times
        for (var i = 0; i < this.form.overtimes.length; i++) {

          this.form.overtimes[i].from = ''
          this.form.overtimes[i].to = ''

        }

        //set first row time

        this.form.overtimes[0].from = def_time_start
        this.form.overtimes[0].to = def_time_end

      }


    },

    addRow: function () {

      this.insertElement(this.form.overtimes.length)



    },

    insertElement: function (index) {
      var self = this;

      if (!this.rowsvalid()) {
        return
      }

      this.myerrors = [];
      var prevrow = (index > 0 && self.form.overtimes.length >= index) ? self.form.overtimes[index - 1] : null;

      this.form.overtimes.splice(index, 0, {
        pen: "",
        designation: "",
        from: prevrow ? prevrow.from : def_time_start,
        to: prevrow ? prevrow.to : def_time_end,
        // worknature: prevrow ? prevrow.worknature : presets_default['default_worknature'],
        // allowpunch_edit: true,
        punching_id : null,

      });

      this.pen_names = []; //clear previos selection from dropdown
      this.pen_names_to_desig = [];


      this.$nextTick(() => {
        self.$refs["field-" + index][0].$el.focus()
      })

    },

    removeElement: function (index) {

      /*if(this.form.overtimes[index].pen == '' || 
         confirm("Remove this row?"))*/
      {

        this.form.overtimes.splice(index, 1);
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
      return `and ${count} other `
    },

    //here, id is the ref property of multiselect which we have set as the index.
    changeSelect(selectedOption, id) {

      //console.log(id)
      this.myerrors = [];
      var self = this
      var desig = self.pen_names_to_desig[selectedOption];
      //alert('changin');
      self.$nextTick(() => {

        self.form.overtimes[id].punching = true
        self.form.overtimes[id].normal_office_hours = 0

        if (desig !== undefined && desig.desig) {
         
          self.form.overtimes[id].designation = desig.desig
          self.form.overtimes[id].punching = desig.category_punching && desig.desig_punching
          self.form.overtimes[id].normal_office_hours = desig.desig_normal_office_hours
          //self.$forceUpdate()
        }

        //set punchtime if not set and available
        //reset for example if user selects another person after selecting a person with punchtime
        // self.form.overtimes[id].allowpunch_edit=true;
        self.form.overtimes[id].punchin = "" 
        self.form.overtimes[id].punchout = ""
        self.form.overtimes[id].punching_id = null
        
        if(desig !== undefined && self.form.overtimes[id].punching ){
          axios.get(urlajaxgetpunchtimes  + '/'+  self.form.duty_date + '/' + self.form.overtimes[id].pen)
          .then(response => {

            console.log('got punch data')
            console.log(response)
            if( response.data && response.data.hasOwnProperty('punchin') && response.data.hasOwnProperty('punchout')  ){
              self.form.overtimes[id].punchin = response.data.punchin;
              self.form.overtimes[id].punchout = response.data.punchout;
              self.form.overtimes[id].punching_id = response.data.id;
              //allow punching edit if there is not associated punching id for this form
              self.form.overtimes[id].allowpunch_edit = response.data.id ? false : true;
            } 
          })
          .catch(err => {
            
          });
        }



      })



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

    },
    updateSelect(selectedOption, id) {
      //not workin
      //alert('updated');

    },
    checkDuplicates() {

      var self = this
      //see if there are duplicates
      var obj = {}
      for (var i = 0; i < self.form.overtimes.length; i++) {
        if (obj[self.form.overtimes[i].pen] == undefined) {
          obj[self.form.overtimes[i].pen] = true
        } else {
          this.myerrors.push('Duplicate name found: ' + self.form.overtimes[i].pen)
          return false
        }
      }

      return true

    },

    checkTimeWithinPunchingTime(punchin, punchout, from, to) {

      var time1 = from.split(":").map(Number);
      var time2 = to.split(":").map(Number);
      //warning: months in JS starts from 0
      var datefrom = Date.UTC(2000, 1, 1, time1[0], time1[1]);
      var dateto = Date.UTC(2000, 1, 1, time2[0], time2[1]);

      //time after 12 am ?
      if (dateto <= datefrom) {
        dateto += 24 * 3600000;
      }

      var time3 = punchin.split(":").map(Number);
      var time4 = punchout.split(":").map(Number);
      //warning: months in JS starts from 0
      var datepunchin = Date.UTC(2000, 1, 1, time3[0], time3[1]);
      var datepunchout = Date.UTC(2000, 1, 1, time4[0], time4[1]);
      if (datepunchout <= datepunchin) {
        datepunchout += 24 * 3600000;
      }

      if (datepunchin > datefrom || datepunchout < dateto) {
        return false
      }

      return true;
    },

    rowsvalid() {


      this.myerrors = [];


      var self = this



      if (self.form.session == '' || self.form.duty_date == '' || self.form.overtime_slot == '') {
        //this.myerrors.push( 'Please select session/date/OT slot' )
        this.$swal('Error', 'Please select session/date/OT', 'error')

        return false
      }



      //check if date belongs to the session

      if (-1 == calenderdays2[self.form.session].indexOf(self.form.duty_date)) {
        this.$swal('Error', 'The duty date is not a calender date for the session: ' + self.form.session, 'error')
        return false
      }


      for (var i = 0; i < self.form.overtimes.length; i++) {

        var row = self.form.overtimes[i];
        if (row.pen == '' || row.designation == '' ||
          row.from == '' || row.to == '' ||
          row.from == null || row.to == null
          //||row.worknature == null || row.worknature == ''

        ) {

          this.$swal('Row: ' + (i + 1), "Fill all the fields in every row", 'error')
          return false
        }

        if(self.form.overtimes[i].punching){
          if ( row.punching && (row.punchin == null || row.punchin == ''
            || row.punchout == null || row.punchout == ''
          )) {

            this.$swal('Row: ' + (i + 1), "Fill punch in/out time for every row", 'error')
            return false
          }
        }

      }




      if (self.form.overtime_slot == 'Additional') {
        if (self.form.overtimes.some(row => row.designation != 'Deputy Secretary' &&
          row.designation != 'Joint Secretary' &&
          row.designation != 'Additional Secretary' &&
          row.designation != 'Special Secretary'
        )) {

          this.$swal('Error', "Only DS or above can have Additional OT!", 'error')
          return false
        }
      }


      //check time diff
      for (var i = 0; i < self.form.overtimes.length; i++) {

        if(self.form.overtimes[i].punching){
        self.form.overtimes[i].punchin = validateHhMm(self.form.overtimes[i].punchin.trim());
        self.form.overtimes[i].punchout = validateHhMm(self.form.overtimes[i].punchout.trim());
        }

        self.form.overtimes[i].from = self.form.overtimes[i].from.trim();
        self.form.overtimes[i].to = self.form.overtimes[i].to.trim();

        self.form.overtimes[i].from = validateHhMm(self.form.overtimes[i].from)
        self.form.overtimes[i].to = validateHhMm(self.form.overtimes[i].to)

      
        if (self.form.overtimes[i].from.toLowerCase() == 'invalid date' ||
          self.form.overtimes[i].to.toLowerCase() == 'invalid date') {
          self.form.overtimes[i].from = self.form.overtimes[i].to = ''
          this.myerrors.push('Row ' + (i + 1) + ': Invalid time format. Enter (HH:MM) in 24 hour format ( examples: 09:30, 17:30).')
          return false
        }

          if (!this.checkTimeWithinPunchingTime(self.form.overtimes[i].punchin, self.form.overtimes[i].punchout, self.form.overtimes[i].from, self.form.overtimes[i].to)) {
          this.myerrors.push('Row ' + (i + 1) + ': OT period should be within punching times')
          return false
        }

        //make sure our times are according to G.O if this is 2nd or 3rd ot on a sitting day
        //note same form can have both part time and full time empl. amspkr office
        if (!iswatchnward &&
          calenderdaysmap[this.form.duty_date] == 'Sitting day') {

          if (self.form.overtimes[i].designation.toLowerCase().indexOf("part time") != -1) {
            //parttime emp

            if (self.form.overtime_slot == 'Second') {

              if (self.form.overtimes[i].from != "14:00" || self.form.overtimes[i].to != "16:30") {

                this.myerrors.push('Row ' + (i + 1) + ': Parttime employees time should be 14:00 to 16:30 as per G.O on a sitting day')
                return false

              }
            } else {
              this.myerrors.push('Row ' + (i + 1) + ': Parttime employees cannot have third OT on a sitting day')
              return false
            }

          }
          else //all other employees and full time for sitting days
          {

            //no need to enforce ending time. have doubts regarding mla hostel. 
            //need to check night shifts

            if (
              (!ispartimefulltime && self.form.overtime_slot == 'First' && (self.form.overtimes[i].from != "08:00" || self.form.overtimes[i].to != "17:30"))
              ||
              (self.form.overtime_slot == 'Second' && (self.form.overtimes[i].from != "17:30" /*|| self.form.overtimes[i].to != "20:00" */))
              ||
              (self.form.overtime_slot == 'Third' && (self.form.overtimes[i].from != "20:00"))) {
              //console.log(self.form.overtimes[i].from)
              this.myerrors.push('Row ' + (i + 1) + ': Time should be as per G.O on a sitting day')
              return false

            }


          }

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

        var diffhours = parseFloat( (dateto - datefrom) / 3600000)

        let minothour = parseFloat( 3) 
        var daytypedesc = 'holiday';

        if (calenderdaysmap[this.form.duty_date].indexOf('oliday') == -1) { //working day or sitting day
          minothour = 2.5;
          daytypedesc = 'sitting/working day'
        }
      //  alert (minothour);

        if (diffhours < minothour) {
          this.myerrors.push('Row ' + (i + 1) + ': At least ' + minothour + ' hours needed for OT on a ' + daytypedesc)
          return false

        }
       
        //new validation after adding normal_office_hours
       
        if (calenderdaysmap[this.form.duty_date].indexOf('oliday') == -1) { //working day or sitting day
          console.log(self.form.overtimes[i].normal_office_hours)
          let othours_needed =  2.5 + self.form.overtimes[i].normal_office_hours
          if (self.form.overtime_slot == 'First' && diffhours < othours_needed) {
            this.myerrors.push(`Row  ${i + 1} : At least ${othours_needed} hours needed for First OT on a ${daytypedesc}`)
            return false
        }
          
        }

        //if this is sitting day, do not allow times between 7.30 am and 5.30 pm

        if (!ispartimefulltime && !iswatchnward && !isspeakeroffice) {
          if (calenderdaysmap[this.form.duty_date] == 'Sitting day') {

            if (self.form.overtime_slot !== 'First') {
              var time730am = Date.UTC(2000, 1, 1, '08', '00');
              var time530pm = Date.UTC(2000, 1, 1, '10', '30'); //skip possible fulltimes

              var isoverlap = ((time730am < dateto) && (time530pm > datefrom)) ||
                (time730am == datefrom || time530pm == dateto);

              if (isoverlap) {
                this.myerrors.push('Row ' + (i + 1) + ': OT cannot be between 8:00 am and 10:30 am on a Sitting day')
                return false
              }
            }

          }
          else
            if (calenderdaysmap[this.form.duty_date].indexOf('oliday') == -1) //workingday
            {
              //working day.
              var time730am = Date.UTC(2000, 1, 1, '10', '15');
              var time530pm = Date.UTC(2000, 1, 1, '17', '15');

              var isoverlap = ((time730am < dateto) && (time530pm > datefrom)) ||
                (time730am == datefrom || time530pm == dateto);

            

              if (isoverlap && diffhours < 9.5) {
                this.myerrors.push('Row ' + (i + 1) + ': Warning - at least 2.5 hours needed for OT on a working day')
                return false
              }
            }



        }

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
        this.$swal('Error', "Need at least one row!", 'error')
        this.isProcessing = false;
        return false
      }

      if (self.form.worknature == '') {

        this.$swal('Error', 'Please enter the nature of work done', 'error')
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
    //  console.log('update 1')

      if (this.isProcessing) {
        return;
      }

      this.isProcessing = true;
    //  console.log('update 2')
      if (!this.rowsvalid()) {
        this.isProcessing = false;
        return
      }



      var self = this

      if (self.form.overtimes.length <= 0) {

        this.$swal('Error', "Need at least one row!", 'error')
        this.isProcessing = false;
        return false
      }
      if (self.form.worknature == '') {

        this.$swal('Error', 'Please enter the nature of work done', 'error')
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

    savepreset: function () {

      var self = this

      // alert (JSON.stringify(presets));

      var pens = []

      for (var i = 0; i < self.form.overtimes.length; i++) {
        var pen_name = self.form.overtimes[i].pen;
        if (pen_name != '') {
          pens.push(pen_name)
        }

      }

      if (pens.length == 0) {
        self.$swal("", "No rows to save", 'error')
        return;
      }



      var obj = {}
      obj['pens'] = pens
      obj['name'] = 'default'


      self.$swal({
        text: 'Enter a name for preset',
        input: 'text',
        inputValue: '',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        useRejections: true,
        inputValidator: function (value) {
          return new Promise(function (resolve, reject) {
            if (value) {
              var found = (presets.indexOf(value) > -1);
              if (found) {
                reject('Preset with same name exists!')
              }
              else {
                resolve()
              }
            } else {
              reject('You need to write something!')
            }
          })
        },

        preConfirm: function (text) {
          return new Promise(function (resolve, reject) {
            obj['name'] = text
            axios.post(urlpresetsubmit, obj).then(response => {

              if (response.data.result == true) {
                resolve()
              } else {
                reject(response.data.error)
              }
            })
              .catch(error => {

                reject(error.response.data)
              })

          })
        },

      }).then(function (result) {
        self.$swal({
          type: 'success', html: 'Saved!', timer: 1500, useRejections: false,
        });
      })



    },

    loadpreset: function () {


      var self = this

      if (presets.length == 0) {
        self.$swal('Sorry, no presets to load. Save a preset first');
        return;
      }

      self.$swal({
        text: 'Load Preset',
        input: 'select',
        inputOptions: presets,
        inputPlaceholder: 'Select preset',
        showCancelButton: true,
        useRejections: false,
        inputValidator: function (value) {
          return new Promise(function (resolve, reject) {
            if (value) {
              resolve()
            } else {
              reject('You need to select something)')
            }
          })
        },
        showLoaderOnConfirm: true,
        preConfirm: function (index) {
          return new Promise(function (resolve, reject) {

            axios.get(urlajaxpresets + '/' + presets[index]).then(response => {


              self.loadpresetdata(response.data)

              resolve()

            })
              .catch(error => {

                reject(error.response.data)
              })

          })
        },

      }).then(function (result) {


      }) //success 

    }, //loadpreset

    loadpresetdata: function (obj) {

      var self = this

      var timefrom = ''
      var timeto = ''
      var worknature = self.presets_default['default_worknature']

      if (this.form.overtime_slot != '') {
        timefrom = def_time_start
        timeto = def_time_end

      }

      if (self.form.overtimes.length > 0) {
        timefrom = self.form.overtimes[0].from
        timeto = self.form.overtimes[0].to
        worknature = self.form.overtimes[0].worknature
      }

      for (var key in obj) {
        if (obj.hasOwnProperty(key)) {

          //we can either clear items or we check for duplicates
          var entryfound = false
          for (var i = 0; i < self.form.overtimes.length; i++) {
            var pen_name = self.form.overtimes[i].pen;
            if (pen_name == key) { entryfound = true; break; }
          }


          if (!entryfound) {
            self.form.overtimes.push({
              pen: key,
              designation: obj[key],
              from: timefrom,
              to: timeto,
              worknature: worknature,

            });
          }

        }
      }


    }, //loadpresetdata



    copytimedownonerow () {
      console.log('copytimedownonerow')
      for (var i = 0; i < this.form.overtimes.length - 1; i++) {

        if (this.form.overtimes[i].from != '' && this.form.overtimes[i].to != '' &&
          this.form.overtimes[i + 1].from == '' && this.form.overtimes[i + 1].to == '') {

          this.form.overtimes[i + 1].from = this.form.overtimes[i].from
          this.form.overtimes[i + 1].to = this.form.overtimes[i].to
          break;
        }

      }

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


