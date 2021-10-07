
function isValidDate(dateStr) {
    // Date validation function courtesty of
    // Sandeep V. Tamhankar (stamhankar@hotmail.com) -->

    // Checks for the following valid date formats:
    // MM/DD/YY   MM/DD/YYYY   MM-DD-YY   MM-DD-YYYY

    var datePat = /^(\d{1,2})(\/|-)(\d{1,2})\2(\d{4})$/; // requires 4 digit year

    var matchArray = dateStr.match(datePat); // is the format ok?
    if (matchArray == null) {
        alert(dateStr + " Date is not in a valid format.")
        return false;
    }
    month = matchArray[1]; // parse date into variables
    day = matchArray[3];
    year = matchArray[4];
    if (month < 1 || month > 12) { // check month range
        alert("Month must be between 1 and 12.");
        return false;
    }
    if (day < 1 || day > 31) {
        alert("Day must be between 1 and 31.");
        return false;
    }
    if ((month==4 || month==6 || month==9 || month==11) && day==31) {
        alert("Month "+month+" doesn't have 31 days!")
        return false;
    }
    if (month == 2) { // check for february 29th
        var isleap = (year % 4 == 0 && (year % 100 != 0 || year % 400 == 0));
        if (day>29 || (day==29 && !isleap)) {
            alert("February " + year + " doesn't have " + day + " days!");
            return false;
        }
    }
    return true;
}

function isValidTime(timeStr) {
    // Time validation function courtesty of
    // Sandeep V. Tamhankar (stamhankar@hotmail.com) -->

    // Checks if time is in HH:MM:SS AM/PM format.
    // The seconds and AM/PM are optional.

    var timePat = /^(\d{1,2}):(\d{2})(:(\d{2}))?(\s?(AM|am|PM|pm))?$/;

    var matchArray = timeStr.match(timePat);
    if (matchArray == null) {
        alert("Time is not in a valid format.");
        return false;
    }
    hour = matchArray[1];
    minute = matchArray[2];
    second = matchArray[4];
    ampm = matchArray[6];

    if (second=="") {
        second = null;
    }
    if (ampm=="") {
        ampm = null
    }

    if (hour < 0  || hour > 23) {
        alert("Hour must be between 1 and 12. (or 0 and 23 for military time)");
        return false;
    }
    if (hour <= 12 && ampm == null) {
        if (confirm("Please indicate which time format you are using.  OK = Standard Time, CANCEL = Military Time")) {
            alert("You must specify AM or PM.");
            return false;
        }
    }
    if  (hour > 12 && ampm != null) {
        alert("You can't specify AM or PM for military time.");
        return false;
    }
    if (minute < 0 || minute > 59) {
        alert ("Minute must be between 0 and 59.");
        return false;
    }
    if (second != null && (second < 0 || second > 59)) {
        alert ("Second must be between 0 and 59.");
        return false;
    }
    return true;
}

function dateDiff(dateform) {
    date1 = new Date();
    date2 = new Date();
    diff  = new Date();

    if (isValidDate(dateform.firstdate.value)) { // Validates first date
        date1temp = new Date(dateform.firstdate.value);
        date1.setTime(date1temp.getTime());
    }
    else return false; // otherwise exits

    if (isValidDate(dateform.seconddate.value)) { // Validates second date
        date2temp = new Date(dateform.seconddate.value);
        date2.setTime(date2temp.getTime());
    }
    else return false; // otherwise exits

    // sets difference date to difference of first date and second date

    diff.setTime(Math.abs(date1.getTime() - date2.getTime()));

    timediff = diff.getTime();

    days = Math.floor(timediff / (1000 * 60 * 60 * 24));
    timediff -= days * (1000 * 60 * 60 * 24);

    dateform.difference.value = weeks + " weeks, " + days + " days, " + hours + " hours, " + mins + " minutes, and " + secs + " seconds";

    return false; // form should never submit, returns false
}
