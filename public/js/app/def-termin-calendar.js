import { date_createObjectFromString } from "./module/date-helpers.js";

// ---------------------------------------------------------------------------
// refresh website if necessary

function handleReload() {
    // init
    let pageDatetime = document.querySelector('body').dataset.pageDatetime;
    let loading_date = date_createObjectFromString(pageDatetime);
    let current_date = new Date();
    // process
    if ((current_date - loading_date) > (60 * 5 * 12 * 1000 * 3)) { // 60 * 5 * 12 * 1000 * 3 => 3 h
        window.removeEventListener('focus', handleReload, false);
        alert("Webseite wird neu geladen.");
        window.location.href = "/?reload=" + pageDatetime;
    }
}

window.addEventListener('focus', handleReload, false);

// ---------------------------------------------------------------------------
// jump to hash if none is given

window.onload = function (event) {
    if (!window.location.hash) {
        const today = (new Date()).getFullYear() + '-'
            + ('0' + ((new Date()).getMonth() + 1)).slice(-2) + '-'
            + ('0' + (new Date()).getDate()).slice(-2);
        window.location.hash = "#anchor-" + today;
    }
}

// ---------------------------------------------------------------------------
// run highlight

const highlightElements = document.querySelectorAll("[data-highlight='1']");

for (let i = 0, len = highlightElements.length; i < len; i++) {
    const listItem = highlightElements[i].closest("li");
    setTimeout(function () { listItem.classList.add("highlight") }, 100 * i);
    listItem.addEventListener("transitionend", () => { listItem.classList.remove("highlight") });
}

// ---------------------------------------------------------------------------