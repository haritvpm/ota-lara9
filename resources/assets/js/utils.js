
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