
export function setEmployeeTypes(row) {
    if( !row.hasOwnProperty("designation") || !row.hasOwnProperty("category") || !row.hasOwnProperty("normal_office_hours") ){
        console.error("setEmployeeTypes - not all Property set");
    }
    console.log("setEmployeeTypes");
    row.isPartime = row.designation.toLowerCase().indexOf("part time") != -1 || 
                    row.category.toLowerCase().indexOf("PartTime") != -1 ||
                    row.designation.toLowerCase().indexOf("parttime") != -1 || 
                    row.normal_office_hours == 3; //ugly
    row.isFulltime = row.category.toLowerCase().indexOf("fulltime") != -1 || 
                    row.normal_office_hours == 6;
    row.isWatchnward = row.category.toLowerCase().indexOf("watch") != -1;
}
export function stringTimeToDate(sTimeWithSemicolonSeperator) {
    var time = sTimeWithSemicolonSeperator.split(":").map(Number);
    //warning: months in JS starts from 0
    return Date.UTC(2000, 1, 1, time[0], time[1]);
};

export function timePeriodIncludesPeriod (from, to, fromReq, toReq)  {
    var datefrom = stringTimeToDate(from)
    var dateto = stringTimeToDate(to) 	
    var time800am = stringTimeToDate(fromReq)
    var time530pm = stringTimeToDate(toReq)
    return time800am >= datefrom && time530pm <= dateto;
 
}
export function  checkDatesAndOT(row, data){
    //we need to give some leeway. so commenting
  let count = 0;

  for (let i = 0; i < data.dates.length; i++) {
    // console.log(data.dates[i])
    const punchin = data.dates[i].punchin;
    const punchout = data.dates[i].punchout;

    if( "N/A" == punchin ){ //no punching day. NIC server down
      data.dates[i].ot = '*'
      continue;
    }

    data.dates[i].ot = 'NO'

    if (row.isPartime) {
      if (timePeriodIncludesPeriod(punchin, punchout, "06:05", "11:25")) {
        data.dates[i].ot = 'YES'
        count++;
      }
    }
    else if (row.isFulltime) {
       if (timePeriodIncludesPeriod(punchin, punchout, "06:05", "16:25")) { 
          count++;
          data.dates[i].ot = 'YES'
        }
    }    
    else if (row.isWatchnward) {
      //no punching
    } //all other employees for sitting days
    else {
        if (timePeriodIncludesPeriod(punchin, punchout, "08:05", "17:25")) {
          count++;
          data.dates[i].ot = 'YES'
        }
    }
  
  }
 
 return {
  count : row.count ,
  modaldata : data.dates
 }

}