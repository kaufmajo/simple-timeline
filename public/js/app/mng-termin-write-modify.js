import {date_getDifferenceIndays} from "./module/date-helpers.js";

// ----------------------------------------------------------------------------
// controls

const imagePreview = document.getElementById("img-image-preview");
const inputImage = document.getElementById("input-termin-image");
const inputBetreff = document.getElementById("input-termin-betreff");
const inputKategorie = document.getElementById("input-termin-kategorie");
const inputIntervall = document.getElementById("select-termin-serie-intervall");
const inputWiederholung = document.getElementById("select-termin-serie-wiederholung");
const inputDatumStart = document.getElementById("input-termin-datum-start");
const inputDatumEnde = document.getElementById("input-termin-datum-ende");
let datumStartPreviousValue;
let datumEndePreviousValue;

// ----------------------------------------------------------------------------
// run when page loads

populateSelectIntervall();

// ----------------------------------------------------------------------------

const doesImageExist = (url) => new Promise((resolve) => {
    const img = new Image();
    img.src = url;
    img.onload = () => resolve(true);
    img.onerror = () => resolve(false);
});

// ----------------------------------------------------------------------------

inputImage.addEventListener("change", function () {
    doesImageExist(inputImage.value).then(function (isImage) {
        if (!(isImage)) imagePreview.src = "/img/image.png";
        else imagePreview.src = inputImage.value;
    });
});

// ----------------------------------------------------------------------------

inputIntervall.addEventListener("change", function () {
    inputWiederholung.value = '';
    populateSelectIntervall();
});

// ----------------------------------------------------------------------------
// set default "betreff" value

inputKategorie.addEventListener("change", function () {
    if ('' === inputBetreff.value) {
        inputBetreff.value = inputKategorie.value;
    }
});

// ----------------------------------------------------------------------------
// set ende date value

inputDatumStart.addEventListener("focus", function () {
    // Store the current value on focus and on change
    datumStartPreviousValue = inputDatumStart.value;
    datumEndePreviousValue = inputDatumEnde.value;
});

inputDatumStart.addEventListener("change", function () {
    // Do something with the previous value after the change
    if (Date.parse(inputDatumEnde.value)) {
        let datumStart = new Date(inputDatumStart.value);
        let datumEnde = new Date(inputDatumStart.value);
        datumEnde.setDate(datumStart.getDate() + date_getDifferenceIndays(datumStartPreviousValue, datumEndePreviousValue));
        inputDatumEnde.value = datumEnde.getFullYear() + '-' + ("0" + (datumEnde.getMonth() + 1)).slice(-2) + '-' + ("0" + (datumEnde.getDate())).slice(-2);
        // Make sure the previous value is updated
        datumStartPreviousValue = inputDatumStart.value;
        datumEndePreviousValue = inputDatumEnde.value;
    }
});

// ----------------------------------------------------------------------------
// populate controls

function populateSelectIntervall() {
    const options = inputWiederholung.querySelectorAll("option");

    for (let i = 0, len = options.length; i < len; i++) {
        if (options[i].dataset.intervall === inputIntervall.value || options[i].value === '') {
            options[i].disabled = false;
            options[i].style.display = "block";
        } else {
            options[i].disabled = true
            options[i].style.display = "none";
        }
    }
}

// ----------------------------------------------------------------------------
