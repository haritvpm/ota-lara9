!function(e){function r(o){if(t[o])return t[o].exports;var s=t[o]={i:o,l:!1,exports:{}};return e[o].call(s.exports,s,s.exports,r),s.l=!0,s.exports}var t={};r.m=e,r.c=t,r.d=function(e,t,o){r.o(e,t)||Object.defineProperty(e,t,{configurable:!1,enumerable:!0,get:o})},r.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return r.d(t,"a",t),t},r.o=function(e,r){return Object.prototype.hasOwnProperty.call(e,r)},r.p="",r(r.s=37)}({37:function(e,r,t){e.exports=t(38)},38:function(e,r){function t(e,r,t){return r in e?Object.defineProperty(e,r,{value:t,enumerable:!0,configurable:!0,writable:!0}):e[r]=t,e}var o;new Vue({el:"#app",data:{isProcessing:!1,form:{},errors:{},myerrors:[],muloptions:designations,pen_names:[],pen_names_to_desig:[],presets:presets,calenderdays2:calenderdays2},created:function(){Vue.set(this.$data,"form",_form),this.sessionchanged()},mounted:function(){},computed:{configdate:function(){return{format:"DD-MM-YYYY",useCurrent:!1,enabledDates:Object.keys(calenderdaysmap).map(function(e){return moment(e,"DD-MM-YYYY").format("YYYY-MM-DD")})}},isActive:function(){}},watch:{},methods:(o={sessionchanged:function(){void 0!=calenderdays2[this.form.session]?(this.form.date_from=calenderdays2[this.form.session][0],this.form.date_to=calenderdays2[this.form.session][calenderdays2[this.form.session].length-1]):(this.form.date_from="",this.form.date_to="")},onChange:function(){this.myerrors=[]},addRow:function(){var e=this;this.rowsvalid()&&(e.form.overtimes.push({pen:"",designation:"",from:e.form.date_from,to:e.form.date_to,count:"",worknature:""}),this.pen_names=[],this.pen_names_to_desig=[],this.$nextTick(function(){e.$refs["field-"+(e.form.overtimes.length-1)][0].$el.focus()}))},removeElement:function(e){(""==this.form.overtimes[e].pen||confirm("Remove this row?"))&&(this.myerrors=[],this.form.overtimes.splice(e,1))},limitText:function(e){return"and "+e+" more"},asyncFind:_.debounce(function(e){this.mydelayedsearch(e),this.myerrors=[]},500),mydelayedsearch:function(e){var r=this;e.length>=3&&axios.get(urlajaxpen+"/"+e).then(function(e){r.pen_names=e.data.pen_names,r.pen_names_to_desig=e.data.pen_names_to_desig}).catch(function(e){})},clearAll:function(){this.pen_names=[],this.pen_names_to_desig=[]}},t(o,"limitText",function(e){return"and "+e+" other countries"}),t(o,"changeSelect",function(e){this.myerrors=[];var r=this;r.$nextTick(function(){for(var t=0;t<r.form.overtimes.length;t++)if(r.form.overtimes[t].pen==e){var o=r.pen_names_to_desig[e];void 0!==o&&(r.form.overtimes[t].designation=o);break}})}),t(o,"checkDuplicates",function(){for(var e=this,r={},t=0;t<e.form.overtimes.length;t++){if(void 0!=r[e.form.overtimes[t].pen])return this.myerrors.push("Duplicate name found: "+e.form.overtimes[t].pen),!1;r[e.form.overtimes[t].pen]=!0}return!0}),t(o,"rowsvalid",function(){this.myerrors=[];var e=this;if(""==e.form.session||""==e.form.date_from||""==e.form.date_to)return this.$swal("Oops","Please select session/dates!","error"),!1;if(void 0==calenderdays2[e.form.session])return this.$swal("Oops","Session calender not valid","error"),!1;if(-1==calenderdays2[e.form.session].indexOf(e.form.date_from)||-1==calenderdays2[e.form.session].indexOf(e.form.date_to))return this.$swal("Oops","Please select a valid from-date/to-date for the selected session","error"),!1;var r=e.form.date_from.split("-").map(Number),t=e.form.date_to.split("-").map(Number),o=new Date(r[2],r[1]-1,r[0]),s=new Date(t[2],t[1]-1,t[0]);if(o>s)return this.$swal("Oops","Date-from cannot be greater than Date-to!","error"),!1;for(var n=0;n<e.form.overtimes.length;n++){var i=e.form.overtimes[n];if(""==i.pen||""==i.designation||""==i.from||""==i.to||null==i.from||null==i.to||+i.count<=0||isNaN(+i.count))return this.$swal("Row: "+(n+1),"Fill all the required fields in each row!","error"),!1}var a=calenderdays2[this.form.session].length;if(e.form.overtimes.some(function(e){return+e.count>a}))return this.myerrors.push("Total sitting days cannot be more than "+a),!1;for(var n=0;n<e.form.overtimes.length;n++){var r=e.form.overtimes[n].from.split("-").map(Number),t=e.form.overtimes[n].to.split("-").map(Number),o=new Date(r[2],r[1]-1,r[0]),s=new Date(t[2],t[1]-1,t[0]);if(o>s)return this.myerrors.push("Row "+(n+1)+": Period-from cannot be greater than period-to"),!1}return this.checkDuplicates()}),t(o,"create",function(){if(!this.isProcessing){if(this.isProcessing=!0,!this.rowsvalid())return void(this.isProcessing=!1);var e=this;if(e.form.overtimes.length<=0)return this.$swal("Oops","Need at least one row!","error"),this.isProcessing=!1,!1;axios.post(urlformsubmit,e.form).then(function(r){r.data.created?window.location.href=urlformsucessredirect+"/"+r.data.id:e.isProcessing=!1}).catch(function(r){e.$swal({type:"error",title:"Error",text:"Please read the error(s) shown in red at the top",timer:2500});var t=r.response;e.isProcessing=!1,e.errors=t.data})}}),t(o,"update",function(){if(!this.isProcessing){if(this.isProcessing=!0,!this.rowsvalid())return void(this.isProcessing=!1);var e=this;if(e.form.overtimes.length<=0)return this.$swal("Oops","Need at least one row!","error"),this.isProcessing=!1,!1;var r=urlformsubmit+"/"+e.form.id;axios.put(r,e.form).then(function(r){r.data.created?window.location.href=urlformsucessredirect+"/"+r.data.id:e.isProcessing=!1}).catch(function(r){e.$swal({type:"error",title:"Error",text:"Please read the error(s) shown in red at the top",timer:2500});var t=r.response;e.isProcessing=!1,e.errors=t.data})}}),t(o,"loadpreset",function(){var e=this;if(0==presets.length)return void e.$swal("Sorry, no presets to load.");e.$swal({text:"Load Preset",input:"select",inputOptions:presets,inputPlaceholder:"Select preset",showCancelButton:!0,useRejections:!1,inputValidator:function(e){return new Promise(function(r,t){e?r():t("You need to select something)")})},showLoaderOnConfirm:!0,preConfirm:function(r){return new Promise(function(t,o){axios.get(urlajaxpresets+"/"+presets[r]).then(function(r){var o=r.data;for(var s in o)if(o.hasOwnProperty(s)){for(var n=!1,i=0;i<e.form.overtimes.length;i++){var a=e.form.overtimes[i].pen;if(a==s){n=!0;break}}n||e.form.overtimes.push({pen:s,designation:o[s],from:e.form.date_from,to:e.form.date_to,count:"",worknature:""})}t()}).catch(function(e){o(e.response.data)})})}}).then(function(e){})}),o)})}});