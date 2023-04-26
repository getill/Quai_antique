/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import "./styles/app.css";

// start the Stimulus application
import "./bootstrap";

console.log("🔪 GET ILL.");

var items = document.querySelectorAll(".nav-link");

const tooltipTriggerList = document.querySelectorAll(
  '[data-bs-toggle="tooltip"]'
);
const tooltipList = [...tooltipTriggerList].map(
  (tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl)
);

items.forEach((item, idx) => {
  item.addEventListener("click", () => {
    ToggleActive(item, idx);
  });
});

function ToggleActive(el, index) {
  el.classList.toggle("active");
  items.forEach((item, idx) => {
    if (idx !== index) {
      item.classList.remove("active");
    }
  });
}

// Easepick
const picker = new easepick.create({
  element: ".datepicker",
  css: ["https://cdn.jsdelivr.net/npm/@easepick/bundle@1.2.1/dist/index.css"],
  zIndex: 10,
  lang: "fr-FR",
  format: "DD/MM/YYYY",
  AmpPlugin: {
    dropdown: {
      months: true,
      years: true,
      minYear: 2023,
      maxYear: 2026,
    },
  },
  LockPlugin: {
    minDate: new Date(),
    minDays: null,
    maxDays: null,
  },
  plugins: ["AmpPlugin", "LockPlugin"],
});

window.onload = () => {
  // QueryString creation
  const params = new URLSearchParams();

  // Functions to execute when selected
  picker.on("select", () => {
    let pickerDate = picker.getDate();
    params.set("date", pickerDate.toLocaleDateString("fr"));

    //Get active URL
    const Url = new URL(window.location.href);

    // AJAX request
    fetch(Url.pathname + "?" + params.toString() + "&ajax=1", {
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
    })
      .then((response) => response.json())
      .then((data) => {
        // Get content
        const content = document.querySelector("#content");

        // Content replacement
        content.innerHTML = data.content;

        // URL Update
        history.pushState({}, null, Url.pathname + "?" + params.toString());
      })
      .catch((e) => alert(e));
  });
};
