!function(e){function s(t){if(n[t])return n[t].exports;var r=n[t]={i:t,l:!1,exports:{}};return e[t].call(r.exports,r,r.exports,s),r.l=!0,r.exports}var n={};s.m=e,s.c=n,s.d=function(e,n,t){s.o(e,n)||Object.defineProperty(e,n,{configurable:!1,enumerable:!0,get:t})},s.n=function(e){var n=e&&e.__esModule?function(){return e.default}:function(){return e};return s.d(n,"a",n),n},s.o=function(e,s){return Object.prototype.hasOwnProperty.call(e,s)},s.p="",s(s.s=39)}({39:function(e,s,n){e.exports=n(40)},40:function(e,s){function n(e,s,n){return s in e?Object.defineProperty(e,s,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[s]=n,e}var t;new Vue({el:"#app",data:{isProcessing:!1,form:{},errors:{},myerrors:[],muloptions:designations,pen_names:[],pen_names_to_desig:[]},created:function(){Vue.set(this.$data,"form",_form),this.sessionchanged()},mounted:function(){},computed:{isActive:function(){}},watch:{},methods:(t={sessionchanged:function(){void 0!=calenderdays2[this.form.session]?(this.form.date_from=calenderdays2[this.form.session][0],this.form.date_to=calenderdays2[this.form.session][calenderdays2[this.form.session].length-1]):(this.form.date_from="",this.form.date_to="")},addRow:function(){var e=this;if(this.rowsvalid()){e.form.exemptions.length>0&&e.form.exemptions[e.form.exemptions.length-1];e.form.exemptions.push({pen:"",designation:"",worknature:""}),this.pen_names=[],this.pen_names_to_desig=[],this.$nextTick(function(){e.$refs["field-"+(e.form.exemptions.length-1)][0].$el.focus()})}},removeElement:function(e){(""==this.form.exemptions[e].pen||confirm("Remove this row?"))&&(this.myerrors=[],this.form.exemptions.splice(e,1))},limitText:function(e){return"and "+e+" more"},asyncFind:_.debounce(function(e){this.mydelayedsearch(e),this.myerrors=[]},500),mydelayedsearch:function(e){var s=this;e.length>=3&&axios.get(urlajaxpen+"/"+e).then(function(e){s.pen_names=e.data.pen_names,s.pen_names_to_desig=e.data.pen_names_to_desig}).catch(function(e){})},clearAll:function(){this.pen_names=[],this.pen_names_to_desig=[]}},n(t,"limitText",function(e){return"and "+e+" other countries"}),n(t,"changeSelect",function(e){this.myerrors=[];var s=this;s.$nextTick(function(){for(var n=0;n<s.form.exemptions.length;n++)if(s.form.exemptions[n].pen==e){s.pen_names_to_desig[e];break}})}),n(t,"checkDuplicates",function(){for(var e=this,s={},n=0;n<e.form.exemptions.length;n++){if(void 0!=s[e.form.exemptions[n].pen])return this.myerrors.push("Duplicate name found: "+e.form.exemptions[n].pen),!1;s[e.form.exemptions[n].pen]=!0}return!0}),n(t,"rowsvalid",function(){this.myerrors=[];var e=this;if(""==e.form.session)return this.$swal("Oops","Please select session!","error"),!1;if(void 0==calenderdays2[e.form.session])return this.$swal("Oops","Session calender not valid","error"),!1;for(var s=0;s<e.form.exemptions.length;s++){var n=e.form.exemptions[s];if(""==n.pen||""==n.designation||""==n.worknature)return this.$swal("Row: "+(s+1),"Fill all the required fields in each row!","error"),!1}return this.checkDuplicates()}),n(t,"create",function(){if(!this.isProcessing){if(this.isProcessing=!0,!this.rowsvalid())return void(this.isProcessing=!1);var e=this;if(e.form.exemptions.length<=0)return this.$swal("Oops","Need at least one row!","error"),this.isProcessing=!1,!1;axios.post(urlformsubmit,e.form).then(function(s){s.data.created?window.location.href=urlformsucessredirect+"/"+s.data.id:e.isProcessing=!1}).catch(function(s){var n=s.response;e.isProcessing=!1,e.errors=n.data})}}),n(t,"update",function(){if(!this.isProcessing){if(this.isProcessing=!0,!this.rowsvalid())return void(this.isProcessing=!1);var e=this;if(e.form.exemptions.length<=0)return this.$swal("Oops","Need at least one row!","error"),this.isProcessing=!1,!1;var s=urlformsubmit+"/"+e.form.id;axios.put(s,e.form).then(function(s){s.data.created?window.location.href=urlformsucessredirect+"/"+s.data.id:e.isProcessing=!1}).catch(function(s){var n=s.response;e.isProcessing=!1,e.errors=n.data})}}),t)})}});