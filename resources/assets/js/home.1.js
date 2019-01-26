  
///detect brwowser
navigator.sayswho = (function(){
    var ua= navigator.userAgent, tem, 
    M= ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
    if(/trident/i.test(M[1])){
        tem=  /\brv[ :]+(\d+)/g.exec(ua) || [];
        return 'IE '+(tem[1] || '');
    }
    if(M[1]=== 'Chrome'){
        tem= ua.match(/\b(OPR|Edge)\/(\d+)/);
        if(tem!= null) return tem.slice(1).join(' ').replace('OPR', 'Opera');
    }
    M= M[2]? [M[1], M[2]]: [navigator.appName, navigator.appVersion, '-?'];
    if((tem= ua.match(/version\/(\d+)/i))!= null) M.splice(1, 1, tem[1]);
    return M.join(' ');
})();


  var vm = new Vue({
  el: '#app',
  data: {
        
        
    },

    mounted: function () {
        
        //alert(navigator.sayswho);
        var browser = navigator.sayswho.toLowerCase()
                
        var x = browser.indexOf('firefox')

        if(-1 != x){

            var ffver = browser.substr(x+7).trim()

             if( parseInt(ffver) < 20 ){

                //sweetalert is non blocking
                 alert('Error!\nPlease update your browser.\nOnly Firefox version 25 or above is supported.');
                 //return false;

             }
        }
       
        if(showloggedinmessage)
        {
          if(displaynameempty)
          {
           
           this.$swal({
                  title: 'Enter Name',
                  text: 'Please enter your name in the Profile page first.',
                  type: 'warning',
                  showCancelButton: false,
                  confirmButtonColor: '#d33',
                  confirmButtonText: 'Change Name',
                  allowOutsideClick:false,

                }).then((result) => {
                  
                   window.location.href =  urlprofile
                  
                }); // swal

           
          }
          else if (isJSorASorSSLevel){


           this.$swal({
                  title: '',
                  html: 'Please make sure your title (<strong>' + title + '</strong>) <br>is displayed correctly at the top right corner.',
                  });

          } 
          else {
            
            this.$swal({
                  title: 'Welcome',
                  text: 'If you are NOT ' + displayname + ', please click Change Name.',
                  //type: 'info',
                  showCancelButton: true,
                  confirmButtonColor: '#d33',
                  cancelButtonColor:  '#3085d6',
                  confirmButtonText: 'Change Name',
                  cancelButtonText: "I'm "+displayname,
                  allowOutsideClick:false,

                }).then((result) => {
                 
                  //if (result.value) 
                  {
                   window.location.href =  urlprofile
                  }
                }); // swal
            
          }
                 
        }
        
    }, 
      // define methods under the `methods` object
  methods: {
   
    
   
  }
})
