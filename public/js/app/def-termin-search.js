// ----------------------------------------------------------------------------
// set focus to input if hash is empty

if (!window.location.hash) {
    document.getElementById("input-search-suchtext").focus();
}

// ---------------------------------------------------------------------------

let inputSearchText = document.getElementById("input-search-suchtext");
inputSearchText.addEventListener("keyup", function (event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        document.getElementById("input-search-submit").click();
    }
});

// ---------------------------------------------------------------------------