
"use strict";
import { checkDatesAndOT, setEmployeeTypes } from './utils.js';


var vm = new Vue({

  el: '#app',

  data: {

    isProcessing: false,
    form: {},
    errors: {},
    myerrors: [],
   // muloptions: designations,
    pen_names: [],
    pen_names_to_desig: [],
    presets: presets,
    calenderdays2: calenderdays2,
    showModal: false,
   
    modaldata: [],
    modaldata_fixedOT: 0,
    modaldata_row:null,
    modaldata_totalOTDays:0,
    //modaldata_seldays:[],
    modaldata_showonly: false
  },

  created: function () {

    for(var i=0; i < _form.overtimes.length; i++){
      //console.log( _form.overtimes[i].overtimesittings)
      //console.log( _form.overtimes[i].overtimesittings_)row.overtimesittings.map(s => s.date);
      _form.overtimes[i].overtimesittings =  _.uniq(_form.overtimes[i].overtimesittings.map(s => s.date));
      //_form.overtimes[i].overtimesittings =  _.uniq(_form.overtimes[i].overtimesittings_);
    }

    Vue.set(this.$data, 'form', _form);
    //copy name to PEN field
		$('[data-widget="pushmenu"]').PushMenu('collapse')

    
  

    this.sessionchanged();

    if (this.form.session != '' && this.form.overtimes.length == 0) { //sessions available for dataentry,
      //and this is a new form, not editing existing
      // this.addRow();
    }

  },
  mounted: function () {
  },

  computed: {
    configdate: function () {
      var self = this
      return {
        //dateFormat: 'd-m-Y',
        //enable: calenderdays2[self.form.session]  
        format: 'DD-MM-YYYY',
        useCurrent: false,
        // useStrict : true,
        //inline: true,
        enabledDates: Object.keys(calenderdaysmap).map(x => moment(x, "DD-MM-YYYY").format('YYYY-MM-DD')),
      }
    },

    isActive: function () {

    },
    yesModalDays: function () {
      return this.modaldata.filter( x => x.ot == 'YES').map( x => x.date )
    },
    yesAndNodaysModalDays: function () {
      return this.modaldata.filter( x => x.ot == 'YES' ||  x.ot == 'NO' || x.userdecision==false ).map( x => x.date )
    },

  },

  watch: {

  },

  methods: {

    sessionchanged: function () {
      this.myerrors = [];
      //this.configdate.enable =  calenderdays2[this.form.session]
      if (calenderdays2[this.form.session] != undefined) {
        this.form.date_from = calenderdays2[this.form.session][0]
        this.form.date_to = calenderdays2[this.form.session][calenderdays2[this.form.session].length - 1]
      } else {
        this.form.date_from = ''
        this.form.date_to = ''
      }



    },

    onChange: function () {
      this.myerrors = [];
      //this.slotoptions = this.slotoptions
      // this.selectdaylabel =  ': ' + calenderdaysmap [this.form.duty_date]
      //this.form.overtime_slot =''
    },

    onRowPeriodChange: function (index) {
			//if( e?.type != 'dp' ) return ; //this func seems to be called twice on date change. this prevents that as the first call does not have that set
		//	console.log(i)
    //reset count to zero
    this.form.overtimes[index].count = ""
    
    this.form.overtimes[index].overtimesittings=null
    this.getSittingOTs(index)
		
		},

    addRow: function () {
      //  var elem = document.createElement('tr');
      var self = this;

      if (!this.rowsvalid()) {
        return
      }

      //var prevrow = self.form.overtimes.length > 0 ? self.form.overtimes[self.form.overtimes.length - 1] : null;
    //do the changes in preset loading too
      self.form.overtimes.push({
        pen: "",
        designation: "",
        from: self.form.date_from,
        to: self.form.date_to,
        count: "",
        worknature: "",
        slots: [],
        aadhaarid: null,
        punching: true, //by default everyone ha punching
        isProcessing: false,
        overtimesittings: null, //days user has worked. important to set null which means user has not selected yes/no for manualentry days
      });

      this.pen_names = []; //clear previos selection from dropdown
      this.pen_names_to_desig = [];


      this.$nextTick(() => {
        self.$refs["field-" + (self.form.overtimes.length - 1)][0].$el.focus()
      })

    },

    removeElement: function (index) {

      if (this.form.overtimes[index].pen == '' ||
        confirm("Remove this row?")) {
        //this.myerrors = [];
        this.form.overtimes.splice(index, 1);
        this.myerrors = [];
        this.errors = {};
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

      var self = this;

      if (query.length >= 3) {
        //axios.headers.common['X-CSRF-TOKEN'] = '{{csrf_token()}}';
        //axios.get('/overtime-allowance/public/admin/employees/ajaxfind/'+ query).then(response => {
        axios.get(urlajaxpen + '/' + query).then(response => {

          // console.log(response.data);
          self.pen_names = response.data.pen_names;
          self.pen_names_to_desig = response.data.pen_names_to_desig;
          //this.isLoading = false
         

        })
          .catch(response => {
            // alert (JSON.stringify(response.data))    // alerts {"myProp":"Hello"};

          });
      }

    },
    modalClosed: function(){
      // console.log(this.modaldata_seldays)  
     
      let yesdays = this.modaldata.filter( x => x.ot == 'YES' && x.userdecision == false ).map( x => x.date )
      let userseldays = this.modaldata.filter( x => x.ot == 'YES' && x.userdecision == true ).map( x => x.date )
      this.modaldata_row.overtimesittings = [ ...new Set([...yesdays, ...userseldays])]
      this.modaldata_row.count = this.modaldata_row.overtimesittings.length
      // console.log(this.modaldata_row.overtimesittings)  
      
      //copy dates from 

		  //vue does not update time if we change date as it does not watch for array changes
		  //https://v2.vuejs.org/v2/guide/reactivity#Change-Detection-Caveats
		  //	Vue.set(this.form.overtimes,index, row)

    },
    showSittingOTs: function(index){
      this.getSittingOTs(index,true)
      
    },
    getSittingOTs: function (index, show=false) {
   
      var self = this;
			let row = self.form.overtimes[index];

			if(row.pen == "" || !self.form.session || !row.from || !row.to){ 
      
       // console.log(self.form.session | row.from | row.to)  
        return
      };
      console.log(row.overtimesittings)  

      self.modaldata = []
      self.modaldata_fixedOT = 0;
      self.modaldata_row = row ;
      row.isProcessing = true;
     
			axios.get(`${urlajaxgetpunchsittings}/${self.form.session}/${row.from}/${row.to}/${row.pen}/${row.aadhaarid}`)
					.then((response) => {
						row.isProcessing = false;
	
						if (response.data) {
              //todo ask if unpresent dates where present
              setEmployeeTypes(row);
              //warning this func modifies response.data
              let {count, modaldata,total_nondecision_days,total_userdecision_days} = checkDatesAndOT(row, response.data);
              //date period may have changed. only include those dates and remove the rest
              //this is to copy the user decided dates to new array.
              //overtimesittings_ has the original data from db
              //let temp =  this.modaldata.filter( x => row.overtimesittings_.indexOf( x.date ) != -1 )
              //row.overtimesittings = [...modaldata.map( x => x.date )]
              if( row.count != count && total_userdecision_days == 0){ //if there are no days that are either MANUALENTRy or NOPUNCHING
                 row.count = count
							  //vue does not update time if we change date as it does not watch for array changes
							  //https://v2.vuejs.org/v2/guide/reactivity#Change-Detection-Caveats
              }
              Vue.set(self.form.overtimes,index, row) //update isProcessing

               if(show){
                  self.modaldata_fixedOT = count;
                  self.modaldata = modaldata
                  self.modaldata_totalOTDays =  total_nondecision_days + total_userdecision_days;
                  //let yesdays = modaldata.filter( x => x.ot == 'YES' && x.userdecision == false ).map( x => x.date )
                  //overtimesittings stores prev selected days/ find only those days from the period
                  //if user changes perod without userdecision and then backagain, this will be lost.
                  //but if user sets and then opens again, we need this
                  //let temp =  row.overtimesittings.filter( date => modaldata.map(d=>d.date).indexOf( date ) != -1 )
                 // self.modaldata_seldays =  [ ...new Set([...yesdays,...temp])]
                  document.getElementById('modalOpenBtn').click()

               }
						}
					})
					.catch((err) => {
            row.isProcessing = false;
		        row.count = 0;
            Vue.set(this.form.overtimes,index, row)
          });
			
    },

    clearAll() {
      this.pen_names = []
      this.pen_names_to_desig = [];
    },

    limitText(count) {
      return `and ${count} other countries`
    },

    changeSelect(selectedOption, id) {

      this.myerrors = [];
      var self = this

      let desig = self.pen_names_to_desig[selectedOption];

      self.$nextTick(() => {
				let row = self.form.overtimes[id];
				row.category = "";
				row.normal_office_hours = 0;
        row.employee_id = null;

        //added no change if a desig already exists
        //to prevent an issue where designation is changeed was wrong
        //try with vince - vincent prasad and dr vincent
        if (desig !== undefined ) {
          row.designation = desig.desig
					row.aadhaarid = desig.aadhaarid;
					row.punching = desig.punching;
					row.normal_office_hours = desig.desig_normal_office_hours ;
          row.category = desig.category;
					row.employee_id = desig.employee_id;
          row.isProcessing= false;
          row.count= "";
          row.overtimesittings=null;

          setEmployeeTypes(row);
          self.getSittingOTs(id)

        }
      })
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

    rowsvalid() {

      this.myerrors = [];

      var self = this


      if (self.form.session == '' || self.form.date_from == '' || self.form.date_to == '') {
        //this.myerrors.push( 'Please select session/dates' )
        this.$swal('Error', "Please select session/dates!", 'error')
        return false
      }

      if (calenderdays2[self.form.session] == undefined) {
        this.$swal('Error', 'Session calender not valid', 'error')
        return false
      }

      if (-1 == calenderdays2[self.form.session].indexOf(self.form.date_from) ||
        -1 == calenderdays2[self.form.session].indexOf(self.form.date_to)) {
        this.$swal('Error', 'Please select a valid from-date/to-date for the selected session', 'error')
        return false
      }


      //check if date from less than date to

      {
        //date.parse returns number of milliseconds elapsed since 1970
        var date1 = self.form.date_from.split("-").map(Number);
        var date2 = self.form.date_to.split("-").map(Number);

        //warning: months in JS starts from 0
        var datefrom = new Date(date1[2], date1[1] - 1, date1[0]);
        var dateto = new Date(date2[2], date2[1] - 1, date2[0]);

        if (datefrom > dateto) { //it can be equial though
          //this.myerrors.push( 'Date-from cannot be greater than Date-to')
          this.$swal('Error', "Date-from cannot be greater than Date-to!", 'error')
          return false
        }

      }



      for (var i = 0; i < self.form.overtimes.length; i++) {

        var row = self.form.overtimes[i];
        if (row.pen == '' || row.designation == '' ||
          row.from == '' || row.to == '' ||
          row.from == null || row.to == null ||
          +row.count <= 0 || isNaN(+row.count)) {

          //this.myerrors.push("Fill all the required fields!");
          this.$swal('Row: ' + (i + 1), "Fill all the required fields in each row!", 'error')
          return false
        }
      }
      var totalsittings = calenderdays2[this.form.session].length;
      if (self.form.overtimes.some(row => +row.count > totalsittings)) {

        this.myerrors.push("Total sitting days cannot be more than " + totalsittings);
        return false
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
          this.myerrors.push('Row ' + (i + 1) + ': Period-from cannot be greater than period-to')
          return false
        }

      }



      return this.checkDuplicates()
    },
    create: function () {

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

        //this.myerrors.push("Fill all the required fields!");
        this.$swal('Error', "Need at least one row!", 'error')
        this.isProcessing = false;
        return false
      }


      axios.post(urlformsubmit, self.form).then(response => {
        //self.$swal.close();
        // alert('success ajax');
        if (response.data.created) {
          window.location.href = urlformsucessredirect + "/" + response.data.id;
        } else {
          self.isProcessing = false;
        }
      })
        .catch(error => {
          //console.log( error.response );
          //self.$swal.close();
          //self.$swal('Error', "!", 'error')
          self.$swal({
            type: 'error',
            title: 'Error',
            text: 'Please read the error(s) shown in red',
           // timer: 2500,

          })

          const response = error.response
          self.isProcessing = false;
          //alert(JSON.stringify(response.data));    // alerts {"myProp":"Hello"};
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

        //this.myerrors.push("Fill all the required fields!");
        this.$swal('Error', "Need at least one row!", 'error')
        this.isProcessing = false;
        return false
      }



      //this.$swal('Please wait')
      //this.$swal.showLoading()

      var updateurl = urlformsubmit + '/' + self.form.id

      axios.put(updateurl, self.form).then(response => {
        //self.$swal.close();
        // alert('success ajax');
        if (response.data.created) {
          window.location.href = urlformsucessredirect + "/" + response.data.id;;
        } else {
          self.isProcessing = false;
        }
      })
        .catch(error => {
          //console.log( error.response );
          self.$swal({
            type: 'error',
            title: 'Error',
            text: 'Please read the error(s) shown in red',
           // timer: 2500,

          })
          //self.$swal.close();
          const response = error.response
          self.isProcessing = false;
          // alert (JSON.stringify(response.data));    // alerts {"myProp":"Hello"};
          // Vue.set(self.$data, 'errors', response.data);
          self.errors = response.data
        })

    },

/*
    loadpreset: function () {


      var self = this

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

              var obj = response.data


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
                      from: self.form.date_from,
                      to: self.form.date_to,
                      count: "",
                      worknature: ""

                    });
                  }

                }
              }

              resolve()

            })
              .catch(error => {

                reject(error.response.data)
              })

          })
        },

      }).then(function (result) {


      }) //success 

    } //loadpreset
    */


  }

})
