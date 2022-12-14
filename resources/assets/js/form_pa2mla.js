
new Vue({
    
        el: '#app',
    
        data: {
          
            isProcessing: false,
            form: {},
            errors: {},
            myerrors: [],
            muloptions: designations,
            pen_names:  pa2mlas,
            pen_names_to_desig_our : pen_names_to_desig,
            addedemployeedesigdisplay : '',

        }, 

        created: function () {
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
                //this.configdate.enable =  calenderdays2[this.form.session]                
                this.form.date_from = calenderdays2[this.form.session][0]
                this.form.date_to = calenderdays2[this.form.session][calenderdays2[this.form.session].length-1]

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

                    this.myerrors = [];
    
                    var prevrow = self.form.overtimes.length > 0 ? self.form.overtimes[self.form.overtimes.length - 1] : null;
                    
                    self.form.overtimes.push({
                        pen: "",
                        designation: "Personal Assistant to MLA",
                      
                        count:prevrow ? prevrow.count : 0,
                        worknature: prevrow ? prevrow.worknature : "",
                        
                    });

                    this.$nextTick(() => {
                        self.$refs["field-" + (self.form.overtimes.length - 1)][0].$el.focus()
                    })
                        
    
                    
            },
            
            removeElement: function(index) {
              this.form.overtimes.splice(index, 1);
              this.myerrors = [];

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
    
            
    
            limitText (count) {
              return `and ${count} other countries`
            },
          
               
            changeSelect (selectedOption, id) {
                
                this.myerrors = [];
                var self = this
                //alert('changin');
                self.$nextTick(() => {
                  //for(var i=0; i < self.form.overtimes.length; i++)
                  for(var i= self.form.overtimes.length-1; i >=0  ; i--)
                  {
                  if(self.form.overtimes[i].pen == selectedOption /*&& 
                     self.form.overtimes[i].designation == ''*/){

                    var desig = self.pen_names_to_desig_our[selectedOption];
                   
                    if(desig !== undefined){

                        if(desig.indexOf('Attendant') != -1){

                            self.form.overtimes[i].designation = 'Office Attendant';
                            self.addedemployeedesigdisplay = '';
                        
                        } else {
                           self.form.overtimes[i].designation = 'Personal Assistant to MLA';
                           var hiphenpos = self.form.overtimes[i].pen.indexOf( '-' );
                           self.addedemployeedesigdisplay = self.form.overtimes[i].pen.substr( hiphenpos+1 ) + ' : '+ desig;
                           if(desig.toUpperCase().indexOf('RELIEVED') != -1){
                             this.myerrors.push('Emp relieved. Check the dates and OT manually: ' + self.form.overtimes[i].pen)
                           }
                        }


                    }
                    break;
                  }

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

                if( -1 == calenderdays2[self.form.session].indexOf(self.form.date_from) ||
                    -1 == calenderdays2[self.form.session].indexOf(self.form.date_to))
                {
                  this.$swal('Oops', 'Please select a valid from-date/to-date for the selected session', 'error')
                  return false 
                }
               
                
                if( self.form.overtimes.some( row =>  row.pen == '' || row.designation == '' || 
                                                                     +row.count <= 0 ))
                {
                   
                    //this.myerrors.push("Fill all the required fields!");
                    this.$swal('Oops', "Fill all the required fields!", 'error')
                    return false
                }
                var totaldays = calenderdays2[this.form.session].length;
                if(self.form.overtimes.some( row => +row.count > totaldays))
                {                
                      
                    this.myerrors.push("Total days cannot be more than " + totaldays);
                    return false
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

               

                
                //this.$swal('Please wait')
                //this.$swal.showLoading()
               
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
                                                                            
                if(!this.rowsvalid()){
                    return
                }
                
                var self = this

                if( self.form.overtimes.length <= 0 )
                {
                   
                    //this.myerrors.push("Fill all the required fields!");
                    this.$swal('Oops', "Need at least one row!", 'error')
                    return false
                }


                this.isProcessing = true;
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
          
          
        }
    
    })
    