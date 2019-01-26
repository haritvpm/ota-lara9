

    Vue.use(VueSweetAlert.default)

    var vm = new Vue({
      el: '#app',
      data: {

      },

      mounted: function () {

      }, 
      // define methods under the `methods` object
      methods: {

        forwardClick() {

            var self = this
            this.$swal({
                titleText: 'Forward form to:',
                input: 'select',
                type : 'question',
                confirmButtonText: '<i class="fa fa-mail-forward"></i> Forward',
                inputOptions: forwardarray,
                //inputPlaceholder: 'Select whom to forward to',
                inputValue: initalvalue,
                // inputclass:'form-control',
                showCancelButton: true,
                showCloseButton : true,
                allowOutsideClick : false,
                allowEnterKey : false,
                focusCancel: true,
                // animation : false,
                inputValidator: function (value) {
                    return new Promise(function (resolve, reject) {
                      if (value != '') {
                            resolve()
                        } else {
                            reject('You need to select a person to forward to!')
                        }
                    })
                },
                showLoaderOnConfirm: true,
                preConfirm: function (value) {
                return new Promise(function (resolve, reject) {
                  
                    var obj = { 'owner' : value};

                    axios.put(urlformforward + "/" + formid, obj).then(response => {
                      
                        if(response.data.result) {
                         resolve()
                        } else {
                         reject('Error, cannot forward')
                        }
                    })
                    .catch( error => {
                        console.log( error.response );
                        reject(error.response.data)
                    })
                  
                })
                },
                allowOutsideClick: false
                }).then(function (result) {
                    self.$swal({
                        type: 'success',
                        html: 'Forwarded!',
                        timer : 1500,
                        useRejections: false,
                        }).then(function (result) {
                           window.location.href = urlredirect
                        })
                })
        },
        submitClick(){
            var self = this
            this.$swal({
                titleText: 'Submit to Accounts:',
                text: 'Are you sure you want to submit this form to Accounts?',
                type : 'question',
                confirmButtonText: '<i class="fa fa-envelope"></i> Submit',
                allowEnterKey : false,
                showCancelButton: true,
                showCloseButton : true,
                focusCancel: true,
                showLoaderOnConfirm: true,
                preConfirm: function () {
                return new Promise(function (resolve, reject) {
                  
                    var obj = { 'owner' : 'admin'};
               
                    axios.put(urlformsubmittoaccounts + "/" + formid, obj).then(response => {
                      
                        if(response.data.result) {
                         resolve()
                        } else {
                         reject('Error, cannot submit')
                        }
                    })
                    .catch( error => {
                        console.log( error.response );
                        reject(error.response.data)
                    })
                  
                })
                },
                
                }).then(function (result) {
                    self.$swal({
                        type: 'success',
                        html: 'Submitted to Accounts!',
                        timer : 1500,
                        useRejections: false, //prevent exception due to uncatched timer event
                        }).then(function (result) {
                           window.location.href = urlredirect
                        })
                })
        },

        sendbackClick(){
            var self = this
            this.$swal({
                titleText: 'Send back to the form creator?',
                input: 'textarea',
                type : 'question',
                inputPlaceholder: 'Enter reason if any',
                inputValue: remarks,
                confirmButtonText: '<i class="fa fa-reply"></i> Send back',
                allowEnterKey : false,
                showCancelButton: true,
                showCloseButton : true,
                focusCancel: true,
                showLoaderOnConfirm: true,
                preConfirm: function (text) {
                return new Promise(function (resolve, reject) {
                  
                    var obj = { 'remarks' : text};
               

                    axios.put(urlformsendback + "/" + formid, obj).then(response => {
                      
                        if(response.data.result) {
                         resolve()
                        } else {
                         reject('Error, cannot send back')
                        }
                    })
                    .catch( error => {
                        console.log( error.response );
                        reject(error.response.data)
                    })
                  
                })
                },
                
                }).then(function (result) {
                    self.$swal({
                        type: 'success',
                        html: 'Sent back!',
                        timer : 1500,
                        useRejections: false,
                        }).then(function (result) {
                           window.location.href = urlredirect
                        })
                })
        },
    }
})