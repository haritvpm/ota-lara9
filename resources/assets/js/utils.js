
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

    if( !sTimeWithSemicolonSeperator ) return null;
    const time = sTimeWithSemicolonSeperator.split(":").map(Number);
    //warning: months in JS starts from 0
    return Date.UTC(2000, 1, 1, time[0], time[1]);
};

export function timePeriodIncludesPeriod (from, to, fromReq, toReq)  {
    if( !from || !to ) return false;
    const datefrom = stringTimeToDate(from)
    const dateto = stringTimeToDate(to) 	
    const time800am = stringTimeToDate(fromReq)
    const time530pm = stringTimeToDate(toReq)

    if( !datefrom || !dateto ) return false;

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
        if (timePeriodIncludesPeriod(punchin, punchout, "06:10", "11:30") || 
            timePeriodIncludesPeriod(punchin, punchout, "07:10", "12:30")) {
          data.dates[i].ot = 'YES'
          count++;
        } else{
          data.dates[i].ot = 'No. (6/7 am - 11:30/12:30)'
        }
      }
      else if (row.isFulltime) {
  console.log(punchin, punchout)
         if (timePeriodIncludesPeriod(punchin, punchout, "07:10", "16:30") || 
             timePeriodIncludesPeriod(punchin, punchout, "07:10", "17:25")) { 
            count++;
            data.dates[i].ot = 'YES'
          } else{
            data.dates[i].ot = 'No. (7 am - 4:30pm/5:30pm)'
          }
      }    
      else if (row.isWatchnward) {
        //no punching
      } //all other employees for sitting days
      else {

          if (timePeriodIncludesPeriod(punchin, punchout, "08:10", "17:30")) {
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
      if (sittingAllowableForNonAebasDay(punchin, punchout, "06:10", "11:30") || 
          sittingAllowableForNonAebasDay(punchin, punchout, "07:10", "12:30")) {
        data.dates[i].userdecision = true 
       
      } else{
        data.dates[i].ot = 'No. (6/7 - 11:30/12:30)'
      }
    }
    else if (row.isFulltime) {

       if (sittingAllowableForNonAebasDay(punchin, punchout, "07:10", "16:30") || 
          sittingAllowableForNonAebasDay(punchin, punchout, "07:10", "17:30")) { 
          data.dates[i].userdecision = true 
        
        } else{
          data.dates[i].ot = 'No. (7 - 4:30pm/5:30pm)'
        }
    }    
    else if (row.isWatchnward) {
      //no punching
    } //all other employees for sitting days
    else {
        if (sittingAllowableForNonAebasDay(punchin, punchout, "08:10", "17:30")) {
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
export function eligibleForSitOT(row, datefrom, dateto)
{
   // Convert punch-in and punch-out times to Date objects
   const punchInTime = new Date(`1970-01-01T${row.punchin}:00`);
   const punchOutTime = new Date(`1970-01-01T${row.punchout}:00`);
   
   // Define the base punch-in time (8:00 AM), maximum allowed punch-in time (8:10 AM) and base punch-out time (5:30 PM)
   const basePunchInTime = new Date('1970-01-01T08:00:00');
   const maxPunchInTime = new Date('1970-01-01T08:10:00');
   const basePunchOutTime = new Date('1970-01-01T17:30:00');
   
   // Check if punch-in time is after 8:10 AM or punch-out time is before 5:25 PM
  if (punchInTime > maxPunchInTime || punchOutTime < basePunchOutTime) {
    return {eligibleForSitOT: false, graceMin: 0};
  }

    // Check if punch-in time is before or at 8:00 AM
    if (punchInTime <= basePunchInTime) {
      // In this case, punch-out time only needs to be 5:30 PM or later
      return {eligibleForSitOT: punchOutTime >= basePunchOutTime, graceMin: 0};
    }
  
   // Calculate the extra minutes after 8:00 AM
   const extraMinutes = (punchInTime - basePunchInTime) / (1000 * 60);
   
   // Calculate the required punch-out time
   const requiredPunchOutTime = new Date(basePunchOutTime.getTime() + extraMinutes * 60 * 1000);
   
   // Check if the actual punch-out time is after or equal to the required punch-out time
   return {eligibleForSitOT: punchOutTime >= requiredPunchOutTime, graceMin: extraMinutes};

}

export function toHoursAndMinutes(totalMinutes) {
  const hours = Math.floor(totalMinutes / 60);
  const minutes = totalMinutes % 60;
  if( hours ) return `${hours}:${padToTwoDigits(minutes)} hour`;
  return `${minutes} min`;
}
export function toHoursAndMinutesBare(totalMinutes) {
  const hours = Math.floor(totalMinutes / 60);
  const minutes = totalMinutes % 60;
  if( hours ) return `${hours}:${padToTwoDigits(minutes)}`;
  return `0:${minutes}`;
}
function padToTwoDigits(num) {
  return num.toString().padStart(2, '0');
}