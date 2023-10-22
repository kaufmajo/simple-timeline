import {date_createObjectFromString} from "./module/date-helpers.js";

// ----------------------------------------------------------------------------
// set focus to input if hash is empty

if (!window.location.hash) {
    document.getElementById("input-search-suchtext").focus();
}

// ---------------------------------------------------------------------------
// refresh website if necessary

function handleReload() {
    // init
    let loading_date = date_createObjectFromString(document.querySelector('body').dataset.pageDatetime);
    let current_date = new Date();
    // process
    if ((current_date - loading_date) > (60 * 5 * 12 * 1000)) { // 60 * 5 * 12 * 1000 => 1 h
        window.removeEventListener('focus', handleReload, false);
        alert("Webseite wird neu geladen.");
        window.location.href = "/";
    }
}

window.addEventListener('focus', handleReload, false);

// ---------------------------------------------------------------------------

let inputSearchText = document.getElementById("input-search-suchtext");
inputSearchText.addEventListener("keyup", function (event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        document.getElementById("input-search-submit").click();
    }
});

// ---------------------------------------------------------------------------