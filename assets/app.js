/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import "./styles/app.css";

const picker = new easepick.create({
  element: ".datepicker",
  css: ["https://cdn.jsdelivr.net/npm/@easepick/bundle@1.2.1/dist/index.css"],
  zIndex: 10,
  lang: "fr-FR",
  format: "DD MMMM YYYY",
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

// Function to log dateData when selected

picker.on("select", () => {
  console.log(picker.getDate());
});
// start the Stimulus application
import "./bootstrap";
