
new Vue({
    
        el: '#app',
    
        data: {
          
            isProcessing: false,
            form: {},
            errors: {},
            myerrors: [],
            //muloptions: designations,
            pen_names:  [],
        }, 

        created: function () {
            Vue.set(this.$data, 'form', _form);
            this.sessionchanged();
        },   
        mounted: function () {
        },
            
        computed: {
                configdate:  function() {
                    var self = this
                    return {
                        //dateFormat: 'd-m-Y',
                        //enable: calenderdays2[self.form.session]  
                         format: 'DD-MM-YYYY',
                    }
                },

                isActive :  function() {

                },
                
             },
            
        watch: {
               
        },

        methods: {
    
            sessionchanged: function ()
            {
                if(calenderdays2[this.form.session] != undefined){
                //this.configdate.enable =  calenderdays2[this.form.session]                
                this.form.date_from = calenderdays2[this.form.session][0]
                this.form.date_to = calenderdays2[this.form.session][calenderdays2[this.form.session].length-1]
                 }else{
                  this.form.date_from = ''
                  this.form.date_to = ''
                }

            },

            onChange: function ()
            {
                this.myerrors = [];
                this.slotoptions = this.slotoptions
                this.selectdaylabel =  ': ' + calenderdaysmap [this.form.duty_date]
                this.form.overtime_slot =''
               
               
            },
           
             addRow: function() {
                //  var elem = document.createElement('tr');
                    var self = this;
                    
                    if(!this.rowsvalid()){
                        return
                    }
    
                    var prevrow = self.form.overtimes.length > 0 ? self.form.overtimes[self.form.overtimes.length - 1] : null;
                    
                    self.form.overtimes.push({
                        pen: "",
                      //  designation: "",
                        from: prevrow ? prevrow.from : self.form.date_from,
                        to: prevrow ? prevrow.to : self.form.date_to,
                        count: "",
                        worknature:  "",
                        
                    });
                        
    
                    this.pen_names = []; //clear previos selection from dropdown

                    this.$nextTick(() => {
                        self.$refs["field-" + (self.form.overtimes.length-1)][0].$el.focus()
                    })
            },
            
            removeElement: function(index) {
              this.form.overtimes.splice(index, 1);
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
    
              if(query.length >= 3)
              {
                     //axios.headers.common['X-CSRF-TOKEN'] = '{{csrf_token()}}';
                     //axios.get('/overtime-allowance/public/admin/employees/ajaxfind/'+ query).then(response => {
                    axios.get(urlajaxpen + '/'+ query).then(response => {
                        
                     // console.log(response.data);
                      this.pen_names = response.data;
                      //this.isLoading = false
                    
                    })
                    .catch(response => {
                       alert (JSON.stringify(response.data))    // alerts {"myProp":"Hello"};
                  
                    });
              }
    
            },
    
            clearAll () {
              this.pen_names = []
            },
    
            limitText (count) {
              return `and ${count} other countries`
            },
          
            changeSelect (index) {
                this.myerrors = [];
                //this.checkDuplicates() //causes issue on change 
                
              },
            checkDuplicates(){
                var self = this
                //see if there are duplicates
                var obj = {}
                for(var i=0; i < self.form.overtimes.length; i++){
                    if( obj[self.form.overtimes[i].pen] == undefined)
                    {
                        obj[self.form.overtimes[i].pen] = true
                    }else{
                        this.myerrors.push('Duplicate name found: ' + self.form.overtimes[i].pen)
                        return false
                    }
                }

                return true

            },

            rowsvalid() {

                this.myerrors = [];

                var self = this
                 

                if(self.form.session == '' || self.form.date_from == '' || self.form.date_to == '')
                {                   
                    //this.myerrors.push( 'Please select session/dates' )
                     this.$swal('Oops', "Please select session/dates!", 'error')
                    return false
                }

                                

                if(calenderdays2[self.form.session] == undefined){
                  this.$swal('Oops', 'Session calender not valid', 'error')
                  return false 
                }

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
               
                
                if(self.form.overtimes.some( row =>  row.pen == '' || /*row.designation == '' || */
                                        row.from  == '' || row.to == '' || 
                                        row.from  == null || row.to == null || 
                                        +row.count <= 0 || isNaN(+row.count) ))
                {
                   
                    //this.myerrors.push("Fill all the required fields!");
                    this.$swal('Oops', "Fill all the required fields in each row!", 'error')
                    return false
                }
                var totalsittings = calenderdays2[this.form.session].length;
                if(self.form.overtimes.some( row => +row.count > totalsittings))
                {                
                      
                    this.myerrors.push("Total sitting days cannot be more than " + totalsittings);
                    return false
                }
               
                //check time diff
                for(var i=0; i < self.form.overtimes.length; i++){
                    //date.parse returns number of milliseconds elapsed since 1970
                    var date1 = self.form.overtimes[i].from.split("-").map(Number);
                    var date2 = self.form.overtimes[i].to.split("-").map(Number);
             
                    //warning: months in JS starts from 0
                    var datefrom    = new Date( date1[2], date1[1]-1,date1[0]);
                    var dateto      = new Date( date2[2], date2[1]-1,date2[0]);


                    if( datefrom > dateto )

                    {
                        this.myerrors.push( 'Row ' + (i+1) + ': Period-from cannot be greater than period-to' )
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
                if( self.form.overtimes.length <= 0 )
                {
                   
                    //this.myerrors.push("Fill all the required fields!");
                    this.$swal('Oops', "Need at least one row!", 'error')
                    this.isProcessing = false;
                    return false
                }
                
                             
                
               // axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
               // axios.defaults.headers.common['X-CSRF-TOKEN'] = '{{csrf_token()}}';
               // axios.post('/overtime-allowance/public/admin/forms', self.form).then(response => {
                axios.post(urlformsubmit, self.form).then(response => {
                    //console.log( response.data );
                   //self.$swal.close();
                    if(response.data.created) {
                        window.location.href = urlformsucessredirect + "/" + response.data.id;
                    } else {
                        self.isProcessing = false;
                    }
                })
                .catch( error => {
                    self.$swal({
                        type: 'error',
                        title:'Error',
                        text:'Please read the error(s) shown in red at the top',
                        timer : 2500,
                        
                    })
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
                
                if( self.form.overtimes.length <= 0 )
                {
                   
                    //this.myerrors.push("Fill all the required fields!");
                    this.$swal('Oops', "Need at least one row!", 'error')
                    this.isProcessing = false;
                    return false
                }
                

                this.isProcessing = true;
                //this.$swal('Please wait')
                //this.$swal.showLoading()
                
                
                var updateurl = urlformsubmit + '/' + self.form.id
             
                axios.put(updateurl, self.form).then(response => {
                    //console.log( response.data );
                    //self.$swal.close();
                    if(response.data.created) {
                        window.location.href = urlformsucessredirect + "/" + response.data.id;;
                    } else {
                        self.isProcessing = false;
                    }
                })
                .catch( error => {
                    self.$swal({
                        type: 'error',
                        title:'Error',
                        text:'Please read the error(s) shown in red at the top',
                        timer : 2500,
                        
                    })
                    //self.$swal.close();
                    const response = error.response
                    self.isProcessing = false;
                   // alert (JSON.stringify(response.data));    // alerts {"myProp":"Hello"};
                   // Vue.set(self.$data, 'errors', response.data);
                    self.errors = response.data
                })

            },

            loadall: function() {

                var self = this
                var prevrow = self.form.overtimes.length > 0 ? self.form.overtimes[self.form.overtimes.length - 1] : null;

                axios.get(urlajaxpresets + '/all' ).then(response => {
                          
                  var obj = response.data
             
                  //alert (JSON.stringify(response.data))    // alerts {"myProp":"Hello"};

                    for (var n = 0; n < obj.length; n++) {
                     
                        var key = obj[n];
                        //we can either clear items or we check for duplicates
                        var entryfound = false
                        for(var i=0; i < self.form.overtimes.length; i++){
                          var pen_name = self.form.overtimes[i].pen;
                          if( pen_name == key ) {entryfound = true; break;}
                        }

                       
                        if(!entryfound)
                        {
                          self.form.overtimes.push({

                                pen: key,
                              
                                from: prevrow ? prevrow.from : self.form.date_from,
                                to: prevrow ? prevrow.to : self.form.date_to,
                                count: "",
                                worknature:  "",
                                
                            });
                        }
                        
                       
                     }

                     
                  
                })
                .catch( error => {
                   
                   
                })
              
             
            }, //loadlall
          
          
          
        }
    
    })
    