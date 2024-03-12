
export function setEmployeeTypes(row) {
    if( !row.hasOwnProperty("designation") || !row.hasOwnProperty("category") || !row.hasOwnProperty("normal_office_hours") ){
        console.error("setEmployeeTypes - not all Property set");
    }
   // console.log("setEmployeeTypes");
    row.isPartime = row.designation.toLowerCase().indexOf("part time") != -1 || 
                    row.category.toLowerCase().indexOf("parttime") != -1 ||
                    row.designation.toLowerCase().indexOf("parttime") != -1 || 
                    row.normal_office_hours == 3; //ugly
    row.isFulltime = row.category.toLowerCase().indexOf("fulltime") != -1 || 
                    row.normal_office_hours == 6;
    row.isWatchnward = row.category.toLowerCase().indexOf("watch") != -1;
    row.isNormal = !row.isPartime && !row.isFulltime && !row.isWatchnward

}
export function stringTimeToDate(sTimeWithSemicolonSeperator) {
    const time = sTimeWithSemicolonSeperator.split(":").map(Number);
    //warning: months in JS starts from 0
    return Date.UTC(2000, 1, 1, time[0], time[1]);
};

export function timePeriodIncludesPeriod (from, to, fromReq, toReq)  {
    const datefrom = stringTimeToDate(from)
    const dateto = stringTimeToDate(to) 	
    const time800am = stringTimeToDate(fromReq)
    const time530pm = stringTimeToDate(toReq)
    return time800am >= datefrom && time530pm <= dateto;
 }
 //check if punchin or out if available, fails
 //if time is not available, it is ok
 export function sittingAllowableForNonAebasDay (from, to, fromReq, toReq)  {
  
  if(from){
    const datefrom = stringTimeToDate(from)
    const time800am = stringTimeToDate(fromReq)
    if( datefrom >  time800am) return false;
  }
  if(to){
    const dateto = stringTimeToDate(to) 	
    const time530pm = stringTimeToDate(toReq)
    if( dateto <  time530pm ) return false;
  }
   
  return true
}


export function  checkDatesAndOT(row, data){
    //we need to give some leeway. so commenting
  let count = 0; //ot given to user
  let total_nondecision_days = 0 ;
  let total_userdecision_days = 0;
  for (let i = 0; i < data.dates.length; i++) {
    // console.log(data.dates[i])
  
    const punchin = data.dates[i].punchin;
    const punchout = data.dates[i].punchout;
    //if user has made all yes/no decisions, row.overtimesittings will not be null. it can be [] or [<dates>]
    const pos = row.overtimesittings ?  row.overtimesittings.indexOf(data.dates[i].date) : -2;
    
    if( punchin && punchout  ){ //punched

      if (row.isPartime) {
        if (timePeriodIncludesPeriod(punchin, punchout, "06:05", "11:25") || 
            timePeriodIncludesPeriod(punchin, punchout, "07:05", "12:25")) {
          data.dates[i].ot = 'YES'
          count++;
        } else{
          data.dates[i].ot = 'No. (6/7 am - 11:30/12:30)'
        }
      }
      else if (row.isFulltime) {
  
         if (timePeriodIncludesPeriod(punchin, punchout, "06:05", "16:25") || 
             timePeriodIncludesPeriod(punchin, punchout, "07:05", "17:25")) { 
            count++;
            data.dates[i].ot = 'YES'
          } else{
            data.dates[i].ot = 'No. (6/7 am - 4:30pm/5:30pm)'
          }
      }    
      else if (row.isWatchnward) {
        //no punching
      } //all other employees for sitting days
      else {

          if (timePeriodIncludesPeriod(punchin, punchout, "08:05", "17:25")) {
            count++;
            data.dates[i].ot = 'YES'
          }else{
            data.dates[i].ot = 'No. (08:00 - 5:30pm)'
          }
      }

      data.dates[i].userdecision = false 
      total_nondecision_days++;
      if(data.dates[i].ot != 'YES' && pos >=0 ) row.overtimesittings.splice(pos,1) //remove from sel if it is NO
      continue;
    } 

    //punchin or out is not available
    if( data.dates[i].aebasday ){
        data.dates[i].userdecision = false 
        data.dates[i].ot = punchin || punchout ? 'Not Punched?' : 'Leave?'
        total_nondecision_days++;
        if( pos >=0 ) row.overtimesittings.splice(pos,1) //remove from sel if it is NO
        continue;
    }

    //non aebasday, check if user has not punched incorrectly when server was failing
    data.dates[i].userdecision = false 

    if (row.isPartime) {
      if (sittingAllowableForNonAebasDay(punchin, punchout, "06:05", "11:25") || 
          sittingAllowableForNonAebasDay(punchin, punchout, "07:05", "12:25")) {
        data.dates[i].userdecision = true 
       
      } else{
        data.dates[i].ot = 'No. (6/7 - 11:30/12:30)'
      }
    }
    else if (row.isFulltime) {

       if (sittingAllowableForNonAebasDay(punchin, punchout, "06:05", "16:25") || 
          sittingAllowableForNonAebasDay(punchin, punchout, "07:05", "17:25")) { 
          data.dates[i].userdecision = true 
        
        } else{
          data.dates[i].ot = 'No. (6/7 - 4:30pm/5:30pm)'
        }
    }    
    else if (row.isWatchnward) {
      //no punching
    } //all other employees for sitting days
    else {
        if (sittingAllowableForNonAebasDay(punchin, punchout, "08:05", "17:25")) {
          data.dates[i].userdecision = true 
      
        }else{
          data.dates[i].ot = 'No. (08:00 - 5:30pm)'
        }
    }
    if(data.dates[i].userdecision){
      data.dates[i].ot = pos  == -2 ? '*' : 'NO' //-2 if user not dtermined
      total_userdecision_days++;
      if(pos  >=0 ){
        data.dates[i].ot = 'YES'
        count++;
      }
    } else {
      total_nondecision_days++
      if(!data.dates[i].userdecision && pos >=0 ) row.overtimesittings.splice(pos,1) //remove from sel if it is NO

    }
  
  }
 
 return {
  count ,
  modaldata : data.dates,
  total_nondecision_days,
  total_userdecision_days
 }

}

export function toHoursAndMinutes(totalMinutes) {
  const hours = Math.floor(totalMinutes / 60);
  const minutes = totalMinutes % 60;
  if( hours ) return `${hours}:${padToTwoDigits(minutes)} hour`;
  return `${minutes} min`;
}

function padToTwoDigits(num) {
  return num.toString().padStart(2, '0');
}