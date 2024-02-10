new Vue({el:"#app",data:{empname:"",emppen:"",sitting_date:moment(new Date,"DD MM YYYY").format("DD-MM-YYYY"),sitting_date_display:"",list:[],myerrors:[],mysuccess:[]},mounted:function(){this.datechange()},watch:{emppen:function(){this.empname="",this.list=[],this.myerrors=[],this.mysuccess=[],this.emppen.length>=3&&this.asyncFind()}},computed:{configdate:function(){return{format:"DD-MM-YYYY",useCurrent:!0,showTodayButton:!0,maxDate:new Date,enabledDates:Object.keys(calenderdaysmap).map((function(e){return moment(e,"DD-MM-YYYY").format("YYYY-MM-DD")}))}}},methods:{asyncFind:_.debounce((function(){this.empname="Searching...",this.mydelayedsearch(this.emppen.trim())}),300),mydelayedsearch:function(e){var t=this;if(t.list=[],this.myerrors=[],this.mysuccess=[],calenderdaysmap.hasOwnProperty(this.sitting_date)){if(e.length>=3){t.empname="Searching...";var a=t.emppen+"|"+moment(t.sitting_date,"DD MM YYYY").format("DD-MM-YYYY");axios.get(urlajaxpen+"/"+a).then((function(e){if(e.data.pen_names.length)for(var a=0;a<e.data.pen_names.length;a++)t.list.push({name:e.data.pen_names[a],desig:e.data.pen_names_to_desig[e.data.pen_names[a]],absent:e.data.pen_names_to_absent[e.data.pen_names[a]]});else t.myerrors.push(t.emppen+" not found")})).catch((function(e){t.myerrors.push(t.emppen+" not found")}))}}else this.myerrors.push("Please select a sitting day")},datechange:function(){calenderdaysmap.hasOwnProperty(this.sitting_date)?0!=moment().diff(moment(this.sitting_date,"DD MM YYYY"),"days")?this.sitting_date_display=moment(this.sitting_date,"DD MM YYYY").fromNow()+" (session: "+calenderdaysmap[this.sitting_date]+")":this.sitting_date_display="Today (session: "+calenderdaysmap[this.sitting_date]+")":this.sitting_date_display="Not a sitting day",this.emppen="",this.myerrors=[],this.mysuccess=[]},mark:function(e){var t=this;this.myerrors=[],this.mysuccess=[];var a=t.list[e].name+"|"+moment(t.sitting_date,"DD MM YYYY").format("DD-MM-YYYY");axios.get(urlajaxpenupdate+"/"+a).then((function(a){a.data?a.data.res?(a.data.absent?t.myerrors.push(t.list[e].name+" marked as absent/late"):t.mysuccess.push(t.list[e].name+" marked as present"),t.list=[],t.list.push({name:a.data.name,desig:a.data.desig,absent:a.data.absent})):t.myerrors.push(t.list[e].name+" unable to change attendance"):t.emppen=""})).catch((function(e){}))}}});