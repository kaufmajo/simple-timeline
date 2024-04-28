// ---------------------------------------------------------------------------

showCornerButton();

// ---------------------------------------------------------------------------
// top corner button (scrollup)

function showCornerButton() {

    const corner_button = document.getElementById('top_corner_button');

    // When the user scrolls down 20px from the top of the document, show the button
    window.addEventListener('scroll', () => {
        if (
            (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) &&
            (document.body.scrollHeight - 100 >= window.scrollY + window.innerHeight)
        ) {
            corner_button.style.display = "block";
        } else {
            corner_button.style.display = "none";
        }
    });
};

// ---------------------------------------------------------------------------
// When the user clicks on the button, scroll to the top of the document

function topFunction() {
    document.body.scrollTop = 0; // For Safari
    document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
}

// ---------------------------------------------------------------------------
// highlight form fields if not empty

const form_fields = document.querySelectorAll('input[type="text"], input[type="date"], input[type="time"], input[type="search"], select, textarea');

for (let i = 0, len = form_fields.length; i < len; i++) {
    //
    if (form_fields[i].value !== '') {
        form_fields[i].style.backgroundColor = 'var(--bs-warning-bg-subtle)';
        form_fields[i].style.color = 'var(--bs-warning-text-emphasis)';
        form_fields[i].style.fontWeight = 'bold';
    }
    //
    form_fields[i].addEventListener("input", function () {
        form_fields[i].style.backgroundColor = null;
        form_fields[i].style.color = null;
        form_fields[i].style.fontWeight = null;
    });

    //form_fields[i].dispatchEvent(new Event('input'));
}

// ---------------------------------------------------------------------------