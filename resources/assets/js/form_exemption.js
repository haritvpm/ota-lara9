

var vm = new Vue({
    
        el: '#app',
    
        data: {
           
            isProcessing: false,
            form: {},
            errors: {},
            myerrors: [],
            muloptions: designations,
            pen_names:  [],
            pen_names_to_desig:  [],
            
        }, 

        created: function () {
            Vue.set(this.$data, 'form', _form);
            this.sessionchanged();
        },   
        mounted: function () {
        },
            
        computed: {
                

                isActive :  function() {

                },
                
             },
            
        watch: {
               
        },

        methods: {
    
            sessionchanged: function ()
            {
                //this.configdate.enable =  calenderdays2[this.form.session]
                if(calenderdays2[this.form.session] != undefined){
                  this.form.date_from = calenderdays2[this.form.session][0]
                  this.form.date_to = calenderdays2[this.form.session][calenderdays2[this.form.session].length-1]
                }else{
                  this.form.date_from = ''
                  this.form.date_to = ''
                }

            

            },

           
           
             addRow: function() {
                //  var elem = document.createElement('tr');
                    var self = this;
                    
                    if(!this.rowsvalid()){
                        return
                    }
    
                    var prevrow = self.form.exemptions.length > 0 ? self.form.exemptions[self.form.exemptions.length - 1] : null;
                    
                    self.form.exemptions.push({
                        pen: "",
                        designation: "",
                        
                        worknature:  "",
                        
                    });
                                                
                    this.pen_names = []; //clear previos selection from dropdown
                    this.pen_names_to_desig = [];

                    
                    this.$nextTick(() => {
                        self.$refs["field-" + (self.form.exemptions.length-1)][0].$el.focus()
                    })

            },
            
            removeElement: function(index) {
                if(this.form.exemptions[index].pen == '' || 
                   confirm("Remove this row?")){
                  this.myerrors = [];
                  this.form.exemptions.splice(index, 1);
                }
            },
    
            limitText (count) {
              return `and ${count} more`
            },
            
            asyncFind: _.debounce( function (query) {
              //  this.isLoading = true
              // Make a request for a user with a given ID
               this.mydelayedsearch(query);
               this.myerrors = [];  
            },500),
    
            mydelayedsearch: function(query) {
    
              var self = this;

              if(query.length >= 3)
              {
                     //axios.headers.common['X-CSRF-TOKEN'] = '{{csrf_token()}}';
                     //axios.get('/overtime-allowance/public/admin/employees/ajaxfind/'+ query).then(response => {
                    axios.get(urlajaxpen + '/'+ query).then(response => {
                        
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
    
            clearAll () {
              this.pen_names = []
              this.pen_names_to_desig = [];
            },
    
            limitText (count) {
              return `and ${count} other countries`
            },
          
            changeSelect (selectedOption) {
                
                this.myerrors = [];
                var self = this
               
                

                self.$nextTick(() => {
                 
                  for(var i=0; i < self.form.exemptions.length; i++){
                    if(self.form.exemptions[i].pen == selectedOption)
                    {
                      var desig = self.pen_names_to_desig[selectedOption];
                      //added no change if a desig already exists
                      //to prevent an issue where designation is changeed was wrong
                      //try with vince - vincent prasad and dr vincent
                      if(desig !== undefined 
                         /*&& self.form.exemptions[i].designation == null*/){
                          //let them enter the correct designation.
                          //self.form.exemptions[i].designation = desig
                        
                        //self.$forceUpdate()
                                                
                        }
                       break;
                    }


                  }

                })

                               
             },

            checkDuplicates(){
                var self = this
                //see if there are duplicates
                var obj = {}
                for(var i=0; i < self.form.exemptions.length; i++){
                    if( obj[self.form.exemptions[i].pen] == undefined)
                    {
                        obj[self.form.exemptions[i].pen] = true
                    }else{
                        this.myerrors.push('Duplicate name found: ' + self.form.exemptions[i].pen)
                        return false
                    }
                }

                return true

            },

            rowsvalid() {

                this.myerrors = [];

                var self = this
                 

                if(self.form.session == '' )
                {                   
                    //this.myerrors.push( 'Please select session/dates' )
                    this.$swal('Oops', "Please select session!", 'error')
                    return false
                }

                if(calenderdays2[self.form.session] == undefined){
                  this.$swal('Oops', 'Session calender not valid', 'error')
                  return false 
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
               
                

                 for(var i=0; i < self.form.exemptions.length; i++){
                   
                  var row  = self.form.exemptions[i];
                  if(row.pen == '' || row.designation == '' || row.worknature == '' )
                  {
                     
                      //this.myerrors.push("Fill all the required fields!");
                      this.$swal('Row: ' + (i+1), "Fill all the required fields in each row!", 'error')
                      return false
                  }
                }
                             
               
                return this.checkDuplicates()
            },
            create: function() {
                
                if(this.isProcessing){

                  return;
                }

                this.isProcessing = true;
              
                if(!this.rowsvalid()){
                    this.isProcessing = false;
                    return
                }
                  
                var self = this   
                if( self.form.exemptions.length <= 0 )
                {
                   
                    //this.myerrors.push("Fill all the required fields!");
                    this.$swal('Oops', "Need at least one row!", 'error')
                    this.isProcessing = false;
                    return false
                }
                
                           
             
   
                axios.post(urlformsubmit, self.form).then(response => {
                    //self.$swal.close();
                   // alert('success ajax');
                    if(response.data.created) {
                        window.location.href = urlformsucessredirect + "/" + response.data.id;
                    } else {
                        self.isProcessing = false;
                    }
                })
                .catch( error => {
                    console.log( error.response );
                    //self.$swal.close();
                    const response = error.response
                    self.isProcessing = false;
                   // alert (JSON.stringify(response.data));    // alerts {"myProp":"Hello"};
                   // Vue.set(self.$data, 'errors', response.data);
                    self.errors = response.data
                })

                
            },


            update: function() {

                if(this.isProcessing){
                  return;
                }

                this.isProcessing = true;
                                                                            
                if(!this.rowsvalid()){
                    this.isProcessing = false;
                    return
                }
                
                var self = this    
                
                if( self.form.exemptions.length <= 0 )
                {
                   
                    //this.myerrors.push("Fill all the required fields!");
                    this.$swal('Oops', "Need at least one row!", 'error')
                    this.isProcessing = false;
                    return false
                }
                

               
                //this.$swal('Please wait')
                //this.$swal.showLoading()
                
                var updateurl = urlformsubmit + '/' + self.form.id
             
                axios.put(updateurl, self.form).then(response => {
                    //self.$swal.close();
                   // alert('success ajax');
                    if(response.data.created) {
                        window.location.href = urlformsucessredirect + "/" + response.data.id;;
                    } else {
                        self.isProcessing = false;
                    }
                })
                .catch( error => {
                    console.log( error.response );
                    //self.$swal.close();
                    const response = error.response
                    self.isProcessing = false;
                   // alert (JSON.stringify(response.data));    // alerts {"myProp":"Hello"};
                   // Vue.set(self.$data, 'errors', response.data);
                    self.errors = response.data
                })

            },

        }
    
    })
    