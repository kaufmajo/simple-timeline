// ----------------------------------------------------------------------------

const checkControl = document.querySelectorAll("[id^='ctrl-check']:not([disabled='disabled'])");
const actionControl = document.querySelectorAll("[id^='ctrl-action']");
let records = [];

// ----------------------------------------------------------------------------

function addItem(item) {
    if (!records.includes(item)) {
        records.push(item);
    }
}

function removeItem(item) {
    let index = records.indexOf(item);
    if (records.indexOf(item) !== -1) {
        records.splice(index, 1);
    }
}

function checkAll(item) {
    for (let z = 0, len = checkControl.length; z < len; z++) {
        if (Number(item.id.match(/[0-9]+$/)[0]) <= Number(checkControl[z].id.match(/[0-9]+$/)[0])) {
            checkControl[z].checked = true;
            addItem(checkControl[z].dataset.id);
        }
    }
}

function displayCounter() {
    // clear all
    checkControl.forEach((control) => {
        document.getElementById(control.id.replace('ctrl-check', 'ctrl-record-counter')).innerText = ''
    });
    // set number
    checkControl.forEach((control) => {
        if (control.checked) document.getElementById(control.id.replace('ctrl-check', 'ctrl-record-counter')).innerText = records.length.toString()
    });
}

// ----------------------------------------------------------------------------
// records

for (let i = 0, len = checkControl.length; i < len; i++) {
    checkControl[i].addEventListener("change", function () {
        // add to records
        if (checkControl[i].checked) {
            if (!records.length) {
                let answer = confirm("Select all records according to the filter?");
                if (answer) {
                    checkAll(checkControl[i]);
                } else {
                    addItem(checkControl[i].dataset.id);
                }
            } else {
                addItem(checkControl[i].dataset.id);
            }
        }
        // remove from records
        else {
            removeItem(checkControl[i].dataset.id);
        }
        // display counter
        displayCounter();
    });
}

// ----------------------------------------------------------------------------
// action

for (let i = 0, len = actionControl.length; i < len; i++) {
    actionControl[i].addEventListener("click", function (event) {
        event.preventDefault();
        // ----------------------------------------------------------------------------
        if (!records.length && actionControl[i].dataset.id) records.push(actionControl[i].dataset.id);
        else if (records.length && !actionControl[i].dataset.id) records = [];
        // ----------------------------------------------------------------------------
        let query = new URLSearchParams();
        for (let z in records) {
            query.append('id[' + z + ']', records[z]);
        }
        // ----------------------------------------------------------------------------
        window.location.href = actionControl[i].href + (records.length ? '?' + query.toString() : '');
    });
}

// ----------------------------------------------------------------------------