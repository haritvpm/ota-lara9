!function(e){function t(o){if(r[o])return r[o].exports;var s=r[o]={i:o,l:!1,exports:{}};return e[o].call(s.exports,s,s.exports,t),s.l=!0,s.exports}var r={};t.m=e,t.c=r,t.d=function(e,r,o){t.o(e,r)||Object.defineProperty(e,r,{configurable:!1,enumerable:!0,get:o})},t.n=function(e){var r=e&&e.__esModule?function(){return e.default}:function(){return e};return t.d(r,"a",r),r},t.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},t.p="",t(t.s=43)}({43:function(e,t,r){e.exports=r(44)},44:function(e,t){function r(e,t,r){return t in e?Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}):e[t]=r,e}function o(e){return moment(e,["HH:mm","h:mm A"]).format("HH:mm")}var s;new Vue({el:"#app",data:{selectdaylabel:"",isProcessing:!1,form:{},errors:{},myerrors:[],pen_names:[],configtime:{format:"HH:mm",stepping:15}},created:function(){Vue.set(this.$data,"form",_form);var e=this;this.$watch("form.duty_date",function(t,r){e.onChange()})},mounted:function(){},computed:{configdate:function(){return{format:"DD-MM-YYYY",useCurrent:!1,enabledDates:Object.keys(calenderdaysmap).map(function(e){return moment(e,"DD-MM-YYYY").format("YYYY-MM-DD")})}},isActive:function(){},slotoptions:function(){if(0==this.form.duty_date.length)return"";switch(calenderdaysmap[this.form.duty_date]){case"Sitting day":return SThirdOT?["Second","Third"]:["Second"];case void 0:return"";default:return NSThirdOT?["First","Second","Third"]:NSSecondOT?["First","Second"]:["First"]}}},watch:{},methods:(s={removeunchecked:function(){for(var e=0;e<this.form.overtimes.length;e++)this.form.overtimes[e].checked||(this.removeElement(e),e--)},copytimedown:function(){if(this.form.overtimes.length>1)for(var e=1;e<this.form.overtimes.length;e++)this.form.overtimes[e].from=this.form.overtimes[0].from,this.form.overtimes[e].to=this.form.overtimes[0].to},copyworknaturedown:function(){if(this.form.overtimes.length>1)for(var e=1;e<this.form.overtimes.length;e++)this.form.overtimes[e].worknature=this.form.overtimes[0].worknature},sessionchanged:function(){this.myerrors=[],""!=this.form.duty_date&&null!=this.form.duty_date&&-1==calenderdays2[this.form.session].indexOf(this.form.duty_date)&&this.myerrors.push("For session "+this.form.session+", please select a date between : "+calenderdays2[this.form.session][0]+" and "+calenderdays2[this.form.session][calenderdays2[this.form.session].length-1]+".")},onChange:function(){this.myerrors=[],this.slotoptions=this.slotoptions,this.form.overtime_slot="",""!=this.form.duty_date&&null!=this.form.duty_date&&(void 0!==calenderdaysmap[this.form.duty_date]?this.selectdaylabel=": "+calenderdaysmap[this.form.duty_date]:this.selectdaylabel=": Not valid for the session",-1==calenderdays2[this.form.session].indexOf(this.form.duty_date)&&this.myerrors.push("For session "+this.form.session+", please select a date between : "+calenderdays2[this.form.session][0]+" and "+calenderdays2[this.form.session][calenderdays2[this.form.session].length-1]+"."))},onChangeSlot:function(){if(def_time_start="17:30",def_time_end="20:30",-1!=calenderdaysmap[this.form.duty_date].indexOf("oliday")||(def_time_end="20:00"),"First"==this.form.overtime_slot&&(def_time_start="14:30",def_time_end="17:30"),this.form.overtimes.length>0){for(var e=0;e<this.form.overtimes.length;e++)this.form.overtimes[e].from="",this.form.overtimes[e].to="";this.form.overtimes[0].from=def_time_start,this.form.overtimes[0].to=def_time_end}},addRow:function(){var e=this;if(this.rowsvalid()){var t=e.form.overtimes.length>0?e.form.overtimes[e.form.overtimes.length-1]:null;e.form.overtimes.push({pen:"",from:t?t.from:def_time_start,to:t?t.to:def_time_end,worknature:t?"-do-":"",checked:!1}),this.pen_names=[],this.$nextTick(function(){e.$refs["field-"+(e.form.overtimes.length-1)][0].$el.focus()})}},removeElement:function(e){this.form.overtimes.splice(e,1)},limitText:function(e){return"and "+e+" more"},asyncFind:_.debounce(function(e){this.mydelayedsearch(e),this.myerrors=[]},500),mydelayedsearch:function(e){var t=this;e.length>=3&&axios.get(urlajaxpen+"/"+e).then(function(e){t.pen_names=e.data}).catch(function(e){alert(JSON.stringify(e.data))})},clearAll:function(){this.pen_names=[]}},r(s,"limitText",function(e){return"and "+e+" other countries"}),r(s,"changeSelect",function(e){this.myerrors=[]}),r(s,"checkDuplicates",function(){for(var e=this,t={},r=0;r<e.form.overtimes.length;r++){if(void 0!=t[e.form.overtimes[r].pen])return this.myerrors.push("Duplicate name found: "+e.form.overtimes[r].pen),!1;t[e.form.overtimes[r].pen]=!0}return!0}),r(s,"rowsvalid",function(){this.myerrors=[];var e=this;if(""==e.form.session||""==e.form.duty_date||""==e.form.overtime_slot)return this.$swal("Oops","Please select session/date/OT slot","error"),!1;if(-1==calenderdays2[e.form.session].indexOf(e.form.duty_date))return this.$swal("Oops","The duty date is not within the range of dates for the session: "+e.form.session,"error"),!1;if(e.form.overtimes.some(function(e){return""==e.pen||""==e.from||""==e.to||null==e.from||null==e.to}))return this.$swal("Oops","Fill all the required fields in every row!","error"),!1;for(var t=0;t<e.form.overtimes.length;t++){if(e.form.overtimes[t].from=e.form.overtimes[t].from.trim(),e.form.overtimes[t].to=e.form.overtimes[t].to.trim(),e.form.overtimes[t].from=o(e.form.overtimes[t].from),e.form.overtimes[t].to=o(e.form.overtimes[t].to),"invalid date"==e.form.overtimes[t].from.toLowerCase()||"invalid date"==e.form.overtimes[t].to.toLowerCase())return e.form.overtimes[t].from=e.form.overtimes[t].to="",this.myerrors.push("Row "+(t+1)+": Invalid time format. Enter (HH:MM) in 24 hour format ( examples: 09:30, 17:30)."),!1;var r=e.form.overtimes[t].from.split(":").map(Number),s=e.form.overtimes[t].to.split(":").map(Number),i=Date.UTC(2e3,1,1,r[0],r[1]),n=Date.UTC(2e3,1,1,s[0],s[1]);n<=i&&(n+=864e5);var a=(n-i)/36e5,m=3,f="holiday";if(-1==calenderdaysmap[this.form.duty_date].indexOf("oliday")&&(m=2.5,f="sitting/working day"),a<m)return this.myerrors.push("Row "+(t+1)+": At least "+m+" hours needed for OT on a "+f),!1}return this.checkDuplicates()}),r(s,"create",function(){if(!this.isProcessing){if(this.isProcessing=!0,!this.rowsvalid())return void(this.isProcessing=!1);var e=this;if(e.form.overtimes.length<=0)return this.$swal("Oops","Need at least one row!","error"),this.isProcessing=!1,!1;axios.post(urlformsubmit,e.form).then(function(t){t.data.created?window.location.href=urlformsucessredirect+"/"+t.data.id:e.isProcessing=!1}).catch(function(t){e.$swal({type:"error",title:"Error",text:"Please read the error(s) shown in red at the top",timer:2500});var r=t.response;e.isProcessing=!1,e.errors=r.data})}}),r(s,"update",function(){if(!this.isProcessing){if(this.isProcessing=!0,!this.rowsvalid())return void(this.isProcessing=!1);var e=this;if(e.form.overtimes.length<=0)return this.$swal("Oops","Need at least one row!","error"),this.isProcessing=!1,!1;var t=urlformsubmit+"/"+e.form.id;axios.put(t,e.form).then(function(t){t.data.created?window.location.href=urlformsucessredirect+"/"+t.data.id:e.isProcessing=!1}).catch(function(t){e.$swal({type:"error",title:"Error",text:"Please read the error(s) shown in red at the top",timer:2500});var r=t.response;e.isProcessing=!1,e.errors=r.data})}}),r(s,"loadall",function(){var e=this;axios.get(urlajaxpresets+"/all").then(function(t){for(var r=t.data,o=0;o<r.length;o++){for(var s=r[o],i=!1,n=0;n<e.form.overtimes.length;n++){if(e.form.overtimes[n].pen==s){i=!0;break}}i||e.form.overtimes.push({pen:s,from:def_time_start,to:def_time_end,worknature:""})}}).catch(function(e){})}),s)})}});