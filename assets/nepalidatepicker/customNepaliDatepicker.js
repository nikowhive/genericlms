
var date = new Date();

// due date
date.setDate(date.getDate() + 7);
var month = date.getMonth() + 1 // month 
var dueDate=date.getFullYear() + "-" + month + "-" + date.getDate();
$('#due_date').val(dueDate);


// english to nepali
let engDate = $("#due_date").val();
let currentDate = new Date(engDate);
let currentNepaliDate = calendarFunctions.getBsDateByAdDate(currentDate.getFullYear(), currentDate.getMonth() + 1, currentDate.getDate());
let formatedNepaliDate = calendarFunctions.bsDateFormat("%y-%m-%d", currentNepaliDate.bsYear, currentNepaliDate.bsMonth, currentNepaliDate.bsDate);
$("#due_date_in_bs").val(formatedNepaliDate);


$("#due_date_in_bs, #issue_date").nepaliDatePicker({
    dateFormat: "%y-%m-%d",
    closeOnDateSelect: true,
    onSelect: function(dateSelect) {
            console.log('dateSelect');
    }
});

$('#due_date_in_bs').on('dateSelect', function(e) {

    let convertedAdDate = e.datePickerData.adDate;
    let finalEngDate = nepToEngByEvent(convertedAdDate);
    $("#due_date").val(finalEngDate);
   
});

let nepToEngByEvent = (adDate) => {

    let newAdYear = adDate.getFullYear();
    let newAdMonth = adDate.getMonth() + 1; 
    let newAdDate = adDate.getDate();
    let engDateWithFormat = newAdYear+'-'+newAdMonth +'-'+newAdDate;
    return engDateWithFormat;
}

function getCurrentNepaliDate(){

    var currentIssueDate = new Date();
    let currentNepaliDate1 = calendarFunctions.getBsDateByAdDate(currentIssueDate.getFullYear(), currentIssueDate.getMonth() + 1, currentIssueDate.getDate());
    let formatedNepaliDate1 = calendarFunctions.bsDateFormat("%y-%m-%d", currentNepaliDate1.bsYear, currentNepaliDate1.bsMonth, currentNepaliDate1.bsDate);
   
    return formatedNepaliDate1;
 }