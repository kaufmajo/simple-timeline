// ---------------------------------------------------------------------------

function date_getDifferenceIndays(date1, date2) {
    // init
    let dt1 = new Date(date1);
    let dt2 = new Date(date2);
    let timeDiff = Math.abs(dt2.getTime() - dt1.getTime());
    // process
    return Math.ceil(timeDiff / (1000 * 3600 * 24));
}

// ---------------------------------------------------------------------------
// parse a datetime in yyyy-mm-dd-hh-mm-ss-ms format

function date_createObjectFromString(value, delimiter = '-') {
    // init
    let parts = value.split(delimiter);
    // new Date(year, month [, day [, hours[, minutes[, seconds[, ms]]]]])
    return new Date(parts[0], parts[1] - 1, parts[2], parts[3], parts[4], parts[5]); // Note: months are 0-based
}

// ---------------------------------------------------------------------------

export {date_getDifferenceIndays, date_createObjectFromString};