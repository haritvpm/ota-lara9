(()=>{"use strict";var e;function t(e){return t="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},t(e)}function r(e,r,o){return(r=function(e){var r=function(e,r){if("object"!==t(e)||null===e)return e;var o=e[Symbol.toPrimitive];if(void 0!==o){var i=o.call(e,r||"default");if("object"!==t(i))return i;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===r?String:Number)(e)}(e,"string");return"symbol"===t(r)?r:String(r)}(r))in e?Object.defineProperty(e,r,{value:o,enumerable:!0,configurable:!0,writable:!0}):e[r]=o,e}var o="17:30",i="20:30";function s(e){return moment(e,["HH:mm","h:mm A"]).format("HH:mm")}var n=new Vue({el:"#app",data:{selectdaylabel:"",isProcessing:!1,form:{},errors:{},myerrors:[],pen_names:[],pen_names_to_desig:[],presets,presets_default,configtime:{format:"HH:mm",stepping:15}},created:function(){Vue.set(this.$data,"form",_form);var e=this;this.$watch("form.duty_date",(function(t,r){e.onChange()})),autoloadpens&&this.loadpresetdata(autoloadpens)},mounted:function(){},computed:{configdate:function(){return{format:"DD-MM-YYYY",useCurrent:!1,enabledDates:Object.keys(calenderdaysmap).map((function(e){return moment(e,"DD-MM-YYYY").format("YYYY-MM-DD")}))}},isActive:function(){},slotoptions:function(){if(0==this.form.duty_date.length)return"";if(ispartimefulltime)return calenderdaysmap[this.form.duty_date],["First","Second"];switch(calenderdaysmap[this.form.duty_date]){case"Sitting day":return["First","Second","Third"];case"Prior holiday":case"Holiday":return["First","Second","Third","Additional"];case void 0:return"";default:return["First","Second","Third"]}}},watch:{},methods:(e={copytimedown:function(){if(this.form.overtimes.length>1)for(var e=1;e<this.form.overtimes.length;e++)this.form.overtimes[e].from=this.form.overtimes[0].from,this.form.overtimes[e].to=this.form.overtimes[0].to},copyworknaturedown:function(){if(this.form.overtimes.length>1)for(var e=1;e<this.form.overtimes.length;e++)this.form.overtimes[e].worknature=this.form.overtimes[0].worknature},sessionchanged:function(){this.myerrors=[],""!=this.form.duty_date&&null!=this.form.duty_date&&-1==calenderdays2[this.form.session].indexOf(this.form.duty_date)&&this.myerrors.push("For session "+this.form.session+", please select a date between : "+calenderdays2[this.form.session][0]+" and "+calenderdays2[this.form.session][calenderdays2[this.form.session].length-1]+".")},onChange:function(){this.myerrors=[],this.slotoptions=this.slotoptions,this.form.overtime_slot="",""!=this.form.duty_date&&null!=this.form.duty_date&&(void 0!==calenderdaysmap[this.form.duty_date]?this.selectdaylabel=": "+calenderdaysmap[this.form.duty_date]:this.selectdaylabel=": Not valid for the session",-1==calenderdays2[this.form.session].indexOf(this.form.duty_date)&&this.myerrors.push("For session "+this.form.session+", please select a date between : "+calenderdays2[this.form.session][0]+" and "+calenderdays2[this.form.session][calenderdays2[this.form.session].length-1]+"."))},onChangeSlot:function(){this.myerrors=[];var e=calenderdaysmap[this.form.duty_date];if(e=void 0!==e?e.toLowerCase():"undefined",o="17:30",i="20:00","First"==this.form.overtime_slot?-1!==e.indexOf("sitting")?(o="8:00",i="17:30",ispartimefulltime&&(o="6:00",i="11:30")):-1!=e.indexOf("working")&&-1==e.indexOf("holiday")?(o="10:15",i="19:45",presets_default&&presets_default.hasOwnProperty("default_workingday_firstot_starttime")&&presets_default.hasOwnProperty("default_workingday_firstot_endtime")&&(o=presets_default.default_workingday_firstot_starttime,i=presets_default.default_workingday_firstot_endtime)):(o="14:30",i="17:30",presets_default&&presets_default.hasOwnProperty("default_holiday_firstot_starttime")&&presets_default.hasOwnProperty("default_holiday_firstot_endtime")&&(o=presets_default.default_holiday_firstot_starttime,i=presets_default.default_holiday_firstot_endtime)):"Second"==this.form.overtime_slot?-1!=e.indexOf("sitting")?(o="17:30",i="20:00",presets_default&&presets_default.hasOwnProperty("default_sittingday_secondot_starttime")&&presets_default.hasOwnProperty("default_sittingday_secondot_endtime")&&(o=presets_default.default_sittingday_secondot_starttime,i=presets_default.default_sittingday_secondot_endtime)):-1!=e.indexOf("working")&&-1==e.indexOf("holiday")?(o="19:45",i="22:15",presets_default&&presets_default.hasOwnProperty("default_workingday_secondot_starttime")&&presets_default.hasOwnProperty("default_workingday_secondot_endtime")&&(o=presets_default.default_workingday_secondot_starttime,i=presets_default.default_workingday_secondot_endtime)):-1!=e.indexOf("holiday")&&(o="17:30",i="20:30",presets_default&&presets_default.hasOwnProperty("default_holiday_secondot_starttime")&&presets_default.hasOwnProperty("default_holiday_secondot_endtime")&&(o=presets_default.default_holiday_secondot_starttime,i=presets_default.default_holiday_secondot_endtime)):"Third"==this.form.overtime_slot?(o="20:30",i="23:30",-1!=e.indexOf("sitting")?(o="20:00",i="22:30"):e.indexOf("holiday")):"Additional"==this.form.overtime_slot&&(o="10:30",i="13:30"),this.form.overtimes.length>0&&""==this.form.overtimes[0].from){for(var t=0;t<this.form.overtimes.length;t++)this.form.overtimes[t].from="",this.form.overtimes[t].to="";this.form.overtimes[0].from=o,this.form.overtimes[0].to=i}},addRow:function(){this.insertElement(this.form.overtimes.length)},insertElement:function(e){var t=this;if(this.rowsvalid()){this.myerrors=[];var r=e>0&&t.form.overtimes.length>=e?t.form.overtimes[e-1]:null;this.form.overtimes.splice(e,0,{pen:"",designation:"",from:r?r.from:o,to:r?r.to:i,punching_id:null}),this.pen_names=[],this.pen_names_to_desig=[],this.$nextTick((function(){t.$refs["field-"+e][0].$el.focus()}))}},removeElement:function(e){this.form.overtimes.splice(e,1),this.myerrors=[]},limitText:function(e){return"and ".concat(e," more")},asyncFind:_.debounce((function(e){this.mydelayedsearch(e),this.myerrors=[]}),500),mydelayedsearch:function(e){var t=this;e.length>=3&&axios.get(urlajaxpen+"/"+e).then((function(e){t.pen_names=e.data.pen_names,t.pen_names_to_desig=e.data.pen_names_to_desig})).catch((function(e){}))},clearAll:function(){this.pen_names=[],this.pen_names_to_desig=[]}},r(e,"limitText",(function(e){return"and ".concat(e," other ")})),r(e,"changeSelect",(function(e,t){this.myerrors=[];var r=this,o=r.pen_names_to_desig[e];r.$nextTick((function(){r.form.overtimes[t].punching=!0,r.form.overtimes[t].normal_office_hours=0,r.form.overtimes[t].category="",void 0!==o&&o.desig&&(r.form.overtimes[t].designation=o.desig,r.form.overtimes[t].punching=o.punching,r.form.overtimes[t].normal_office_hours=o.desig_normal_office_hours,r.form.overtimes[t].category=o.category,r.form.overtimes[t].employee_id=o.employee_id),r.form.overtimes[t].punchin="",r.form.overtimes[t].punchout="",r.form.overtimes[t].punching_id=null,void 0!==o&&r.form.overtimes[t].punching&&axios.get(urlajaxgetpunchtimes+"/"+r.form.duty_date+"/"+r.form.overtimes[t].pen).then((function(e){console.log("got punch data"),console.log(e),e.data&&e.data.hasOwnProperty("punchin")&&e.data.hasOwnProperty("punchout")&&(r.form.overtimes[t].punchin=e.data.punchin,r.form.overtimes[t].punchout=e.data.punchout,r.form.overtimes[t].punching_id=e.data.id,r.form.overtimes[t].allowpunch_edit=!e.data.id)})).catch((function(e){}))}))})),r(e,"updateSelect",(function(e,t){})),r(e,"checkDuplicates",(function(){for(var e=this,t={},r=0;r<e.form.overtimes.length;r++){if(null!=t[e.form.overtimes[r].pen])return this.myerrors.push("Duplicate name found: "+e.form.overtimes[r].pen),!1;t[e.form.overtimes[r].pen]=!0}return!0})),r(e,"stringTimeToDate",(function(e){var t=e.split(":").map(Number);return Date.UTC(2e3,1,1,t[0],t[1])})),r(e,"checkTimeWithinPunchingTime",(function(e){if(!e.punching)return!0;var t=this.stringTimeToDate(e.from),r=this.stringTimeToDate(e.to);r<=t&&(r+=864e5);var o=this.stringTimeToDate(e.punchin),i=this.stringTimeToDate(e.punchout);return i<=o&&(i+=864e5),!(o>t||i<r)})),r(e,"checkTimeIsAsPerGOOnSittingDay",(function(e,t){if(t.isPartime);else if(t.isFulltime);else if(t.isWatchnward);else if("First"==e&&("08:00"!=t.from||"17:30"!=t.to)||"Second"==e&&"17:30"!=t.from||"Third"==e&&"20:00"!=t.from)return!1;return!0})),r(e,"checkIfOTOverlapsWithOfficeHours",(function(e,t,r,o,i){if("First"===e)return!0;var s=o.split(":").map(Number),n=i.split(":").map(Number),a=Date.UTC(2e3,1,1,s[0],s[1]),m=Date.UTC(2e3,1,1,n[0],n[1]);return!(a<r&&m>t||a==t||m==r)})),r(e,"rowsvalid",(function(){this.myerrors=[];var e=this;if(""==e.form.session||""==e.form.duty_date||""==e.form.overtime_slot)return this.$swal("Error","Please select session/date/OT","error"),!1;if(-1==calenderdays2[e.form.session].indexOf(e.form.duty_date))return this.$swal("Error","The duty date is not a calender date for the session: "+e.form.session,"error"),!1;for(var t=0;t<e.form.overtimes.length;t++){if(""==(f=e.form.overtimes[t]).pen||""==f.designation||""==f.from||""==f.to||null==f.from||null==f.to)return this.$swal("Row: "+(t+1),"Fill all the fields in every row","error"),!1;if(e.form.overtimes[t].punching&&f.punching&&(null==f.punchin||""==f.punchin||null==f.punchout||""==f.punchout))return this.$swal("Row: "+(t+1),"Fill punch in/out time for every row","error"),!1}if("Additional"==e.form.overtime_slot&&e.form.overtimes.some((function(e){return"Deputy Secretary"!=e.designation&&"Joint Secretary"!=e.designation&&"Additional Secretary"!=e.designation&&"Special Secretary"!=e.designation})))return this.$swal("Error","Only DS or above can have Additional OT!","error"),!1;var r="Sitting day"==calenderdaysmap[this.form.duty_date],o=-1==calenderdaysmap[this.form.duty_date].indexOf("oliday"),i=-1!=calenderdaysmap[this.form.duty_date].indexOf("orking"),n=(calenderdaysmap[this.form.duty_date].indexOf("oliday"),e.form.overtime_slot),a=parseFloat(3),m="holiday";o&&(a=2.5,m="working day",r&&(m="sitting day"));for(t=0;t<e.form.overtimes.length;t++){var f=e.form.overtimes[t];if(console.log(f),f.isPartime=-1!=f.designation.toLowerCase().indexOf("part time"),f.isFulltime=-1!=f.category.toLowerCase().indexOf("FullTime"),f.isWatchnward=-1!=f.category.toLowerCase().indexOf("Watch"),f.punching&&(f.punchin=s(f.punchin.trim()),f.punchout=s(f.punchout.trim())),f.from=s(f.from.trim()),f.to=s(f.to.trim()),"invalid date"==f.from.toLowerCase()||"invalid date"==f.to.toLowerCase())return f.from=f.to="",this.myerrors.push("Row "+(t+1)+": Invalid time format. Enter (HH:MM) in 24 hour format ( examples: 09:30, 17:30)."),!1;if(!this.checkTimeWithinPunchingTime(f))return this.myerrors.push("Row "+(t+1)+": OT period should be within punching times"),!1;if(r&&!this.checkTimeIsAsPerGOOnSittingDay(n,f))return this.myerrors.push("Row "+(t+1)+": Time should be as per G.O on a sitting day"),!1;var d=this.stringTimeToDate(f.from),u=this.stringTimeToDate(f.to);u<=d&&(u+=864e5);var l=parseFloat((u-d)/36e5);if(l<a)return this.myerrors.push("Row "+(t+1)+": At least "+a+" hours needed for OT on a "+m),!1;if(o){var h=2.5+f.normal_office_hours;if("First"==n&&l<h)return this.myerrors.push("Row  ".concat(t+1," : At least ").concat(h," hours needed for First OT on a ").concat(m)),!1}if(f.isPartime)if(r){if("Third"===n)return this.myerrors.push("Row "+(t+1)+": Parttime employees cannot have third OT on sitting day"),!1}else if(i&&"First"!==n)return this.myerrors.push("Row "+(t+1)+": Parttime employees cannot have second/third OT on working day"),!1;if(f.isFulltime&&o&&"First"!==n)return this.myerrors.push("Row "+(t+1)+": FullTime employees cannot have second/third OT on working/sitting day"),!1;if(!(f.isPartime||f.isFulltime||f.isWatchnward||isspeakeroffice)&&(r||i)&&"First"!==n){var c="10:15",p="17:15";if(r&&(c="08:00",p="17:30"),!this.checkIfOTOverlapsWithOfficeHours(n,d,u,c,p))return this.myerrors.push("Row ".concat(t+1," : OT cannot be between ").concat(c," and ").concat(p," am on a ").concat(m)),!1}}return this.checkDuplicates()})),r(e,"create",(function(){if(!this.isProcessing){if(this.isProcessing=!0,this.rowsvalid()){var e=this;return e.form.overtimes.length<=0?(this.$swal("Error","Need at least one row!","error"),this.isProcessing=!1,!1):""==e.form.worknature?(this.$swal("Error","Please enter the nature of work done","error"),this.isProcessing=!1,!1):void axios.post(urlformsubmit,e.form).then((function(t){t.data.created?window.location.href=urlformsucessredirect+"/"+t.data.id:e.isProcessing=!1})).catch((function(t){e.$swal({type:"error",title:"Error",text:"Please see the error(s) shown in red at the top",timer:2500});var r=t.response;e.isProcessing=!1,e.errors=r.data}))}this.isProcessing=!1}})),r(e,"update",(function(){if(!this.isProcessing)if(this.isProcessing=!0,this.rowsvalid()){var e=this;if(e.form.overtimes.length<=0)return this.$swal("Error","Need at least one row!","error"),this.isProcessing=!1,!1;if(""==e.form.worknature)return this.$swal("Error","Please enter the nature of work done","error"),this.isProcessing=!1,!1;var t=urlformsubmit+"/"+e.form.id;axios.put(t,e.form).then((function(t){t.data.created?window.location.href=urlformsucessredirect+"/"+t.data.id:e.isProcessing=!1})).catch((function(t){e.$swal({type:"error",title:"Error",text:"Please read the error(s) shown in red at the top",timer:2500});var r=t.response;e.isProcessing=!1,e.errors=r.data}))}else this.isProcessing=!1})),r(e,"savepreset",(function(){for(var e=this,t=[],r=0;r<e.form.overtimes.length;r++){var o=e.form.overtimes[r].pen;""!=o&&t.push(o)}if(0!=t.length){var i={};i.pens=t,i.name="default",e.$swal({text:"Enter a name for preset",input:"text",inputValue:"",showCancelButton:!0,showLoaderOnConfirm:!0,useRejections:!0,inputValidator:function(e){return new Promise((function(t,r){e?presets.indexOf(e)>-1?r("Preset with same name exists!"):t():r("You need to write something!")}))},preConfirm:function(e){return new Promise((function(t,r){i.name=e,axios.post(urlpresetsubmit,i).then((function(e){1==e.data.result?t():r(e.data.error)})).catch((function(e){r(e.response.data)}))}))}}).then((function(t){e.$swal({type:"success",html:"Saved!",timer:1500,useRejections:!1})}))}else e.$swal("","No rows to save","error")})),r(e,"loadpreset",(function(){var e=this;0!=presets.length?e.$swal({text:"Load Preset",input:"select",inputOptions:presets,inputPlaceholder:"Select preset",showCancelButton:!0,useRejections:!1,inputValidator:function(e){return new Promise((function(t,r){e?t():r("You need to select something)")}))},showLoaderOnConfirm:!0,preConfirm:function(t){return new Promise((function(r,o){axios.get(urlajaxpresets+"/"+presets[t]).then((function(t){e.loadpresetdata(t.data),r()})).catch((function(e){o(e.response.data)}))}))}}).then((function(e){})):e.$swal("Sorry, no presets to load. Save a preset first")})),r(e,"loadpresetdata",(function(e){var t=this,r="",s="",n=t.presets_default.default_worknature;for(var a in""!=this.form.overtime_slot&&(r=o,s=i),t.form.overtimes.length>0&&(r=t.form.overtimes[0].from,s=t.form.overtimes[0].to,n=t.form.overtimes[0].worknature),e)if(e.hasOwnProperty(a)){for(var m=!1,f=0;f<t.form.overtimes.length;f++){if(t.form.overtimes[f].pen==a){m=!0;break}}m||t.form.overtimes.push({pen:a,designation:e[a].desig,from:r,to:s,worknature:n,category:e[a].category,employee_id:e[a].employee_id,punching:e[a].punching})}})),r(e,"copytimedownonerow",(function(){console.log("copytimedownonerow");for(var e=0;e<this.form.overtimes.length-1;e++)if(""!=this.form.overtimes[e].from&&""!=this.form.overtimes[e].to&&""==this.form.overtimes[e+1].from&&""==this.form.overtimes[e+1].to){this.form.overtimes[e+1].from=this.form.overtimes[e].from,this.form.overtimes[e+1].to=this.form.overtimes[e].to;break}})),e)});window.addEventListener("keydown",(function(e){if(!e.defaultPrevented){var t=!1;void 0!==e.key?"F4"==e.key?(n.copytimedownonerow(),t=!0):"`"==e.key&&(n.addRow(),t=!0):void 0!==e.keyIdentifier||e.keyCode,t&&e.preventDefault()}}),!0)})();